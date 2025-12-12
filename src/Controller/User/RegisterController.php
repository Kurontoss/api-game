<?php

namespace App\Controller\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\ValidationService;
use App\Entity\User;
use App\DTO\User\CreateDTO;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Exception\EmailAlreadyRegisteredException;

final class RegisterController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private JWTTokenManagerInterface $jwt,
        private ValidationService $validator,
        private RegisterService $registerService,
        private UserRepository $userRepo,
    ) {}

    #[Route('/api/register', name: 'user_register', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['user:write']]
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $user = new User();
        $user->setEmail($dto->email);
        $user->setName($dto->name);
        $user->setPassword($dto->password);

        try {
            $this->registerService->register($user);
        } catch (EmailAlreadyRegisteredException $e) {
            throw new BadRequestHttpException('Email is already registered.');
        }

        $this->userRepo->save($user);

        $token = $this->jwt->create($user);

        return $this->json([
            'user' => $this->serializer->normalize($user, 'json',['groups' => ['user:read']]),
            'token' => $token
        ], 201);
    }
}
