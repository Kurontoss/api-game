<?php

namespace App\Controller\User;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;

final class RegisterController extends AbstractController
{
    #[Route('/api/register', name: 'user_register', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        JWTTokenManagerInterface $jwt,
        RegisterService $registerService,
        UserRepository $repository
    ): JsonResponse
    {
        $user = $serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            ['groups' => ['user:write']]
        );

        $registerService->register($user);

        $repository->save($user);

        $token = $jwt->create($user);

        return $this->json([
            'user' => $serializer->normalize($user, 'json',['groups' => ['user:read']]),
            'token' => $token
        ], 201);
    }
}
