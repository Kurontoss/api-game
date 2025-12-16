<?php

namespace App\Controller\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\UserAssembler;
use App\DTO\User\CreateDTO;
use App\Entity\User;
use App\Exception\EmailAlreadyRegisteredException;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use App\Service\ValidationService;

final class RegisterController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private JWTTokenManagerInterface $jwt,
        private ValidationService $validator,
        private RegisterService $registerService,
        private UserRepository $userRepo,
        private UserAssembler $assembler,
    ) {}

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

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $user = $this->assembler->fromCreateDTO($dto);

        try {
            $this->registerService->register($user);
        } catch (EmailAlreadyRegisteredException $e) {
            throw new BadRequestHttpException('Email is already registered');
        }

        $this->userRepo->save($user);

        $token = $this->jwt->create($user);

        return $this->json([
            'user' => $this->serializer->normalize($user, 'json',['groups' => ['user:read']]),
            'token' => $token
        ], 201);
    }
}
