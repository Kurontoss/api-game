<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\UserRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $userRepo,
    ) {}

    #[Route('/api/users/{id}', name: 'user_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $user = $this->userRepo->find($id);

        if (!$user) {
            throw new NotFoundHttpException('Dungeon not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($user, 'json', ['groups' => [
                'user:read',
                'user_knights:read',
                'knight:read',
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
