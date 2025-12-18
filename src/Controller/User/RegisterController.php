<?php

namespace App\Controller\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\UserAssembler;
use App\DTO\ResponseErrorDTO;
use App\DTO\User\CreateDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Service\ValidationService;
use App\Service\Validator\User\CreateUpdateDTOValidator;

final class RegisterController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private JWTTokenManagerInterface $jwt,
        private ValidationService $validationService,
        private CreateUpdateDTOValidator $createDTOValidator,
        private RegisterService $registerService,
        private UserRepository $userRepo,
        private UserAssembler $assembler,
    ) {}

    #[OA\Tag(name: 'Users')]
    #[OA\Post(
        summary: 'Register a user',
        description: 'Registers a new user.',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'User registration payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['user:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'User successfully registered',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: User::class,
                        groups: ['user:read']
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
    #[Route('/api/register', name: 'user_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['user:write']]
        );

        $response = $this->validationService->validate($dto);
        $response->errors = array_merge($response->errors, $this->createDTOValidator->validate($dto));

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = $this->assembler->fromCreateDTO($dto);

        $this->registerService->register($user);

        $this->userRepo->save($user);

        $token = $this->jwt->create($user);

        return $this->json([
            'user' => $this->serializer->normalize($user, 'json',['groups' => ['user:read']]),
            'token' => $token
        ], JsonResponse::HTTP_CREATED);
    }
}
