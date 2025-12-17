<?php

namespace App\Controller\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\DTO\ResponseErrorDTO;
use App\Repository\UserRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepo,
    ) {}

    #[OA\Delete(
        summary: 'Delete a user',
        description: 'Deletes a user with a given id. Requires the currently logged in user to be the user which is to be deleted.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user to delete',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_NO_CONTENT,
                description: 'User successfully deleted'
            ),
            new OA\Response(
                response: JsonResponse::HTTP_NOT_FOUND,
                description: 'Not found',
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
