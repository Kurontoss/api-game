<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\UserRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $userRepo,
    ) {}
    
    #[Route('/api/users', name: 'user_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $users = $this->userRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($users, 'json', ['groups' => ['user:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
