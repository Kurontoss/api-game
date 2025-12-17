<?php

namespace App\Controller\Knight;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\KnightAssembler;
use App\DTO\Knight\CreateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Knight;
use App\Repository\KnightRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private KnightRepository $knightRepo,
        private KnightAssembler $assembler,
    ) {}

    #[OA\Post(
        summary: 'Create a knight',
        description: 'Creates a new knight. Knight\'s user is set to the current user',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Knight creation payload.',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['knight:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Knight successfully created',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Knight::class,
                        groups: ['knight:read']
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
            ),
            new OA\Response(
                response: JsonResponse::HTTP_FORBIDDEN,
                description: 'Access denied',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
    #[Route('/api/knights', name: 'knight_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['knight:write']]
        );

        $response = $this->validationService->validate($dto);

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $knight = $this->assembler->fromCreateDTO($dto);

        $this->knightRepo->save($knight);

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'item_instance:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_CREATED
        );
    }
}
