<?php

namespace App\Controller\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\UserAssembler;
use App\DTO\User\UpdateDTO;
use App\Entity\User;
use App\Exception\EmailAlreadyRegisteredException;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Service\ValidationService;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private JWTTokenManagerInterface $jwt,
        private ValidationService $validator,
        private RegisterService $registerService,
        private UserRepository $userRepo,
        private UserAssembler $assembler,
    ) {}

    #[Route('/api/users/{id}', name: 'user_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        if ($this->getUser()->getId() !== $id) {
            throw new AccessDeniedException('Not allowed to update this user');
        }

        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json',
            ['groups' => ['user:write']]
        );

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepo->find($id);

        $user = $this->assembler->fromUpdateDTO($dto, $user);

        try {
            $this->registerService->update($user);
        } catch (EmailAlreadyRegisteredException $e) {
            throw new BadRequestHttpException('Email is already registered');
        }

        $this->userRepo->save($user);

        $data = [
            'user' => $this->serializer->normalize($user, 'json',['groups' => ['user:read']])
        ];

        if ($dto->email || $dto->password) {
            $data['token'] = $this->jwt->create($user);
        }

        return $this->json($data, JsonResponse::HTTP_CREATED);
    }
}
