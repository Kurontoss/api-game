<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Repository\UserRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepo,
    ) {}

    #[Route('/api/users/{id}', name: 'user_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $user = $this->userRepo->find($id);

        if ($this->getUser()->getId() !== $id) {
            throw new AccessDeniedException('The currently logged in user is not allowed to delete this user');
        }

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->userRepo->delete($user);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
