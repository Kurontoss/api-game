<?php

namespace App\Controller\Knight;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

use App\DTO\ResponseErrorDTO;
use App\Repository\KnightRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private KnightRepository $knightRepo,
    ) {}

    #[OA\Delete(
        summary: 'Delete a knight',
        description: 'Deletes a knight with a given id. Requires the logged in user to be the same as the knight\'s user.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the knight to delete',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_NO_CONTENT,
                description: 'Knight successfully deleted'
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
    #[Route('/api/knights/{id}', name: 'knight_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $knight = $this->knightRepo->find($id);

        if ($this->getUser() !== $knight->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Not authorized to delete this knight');
        }

        if (!$knight) {
            throw new NotFoundHttpException('Knight not found');
        }

        $this->knightRepo->delete($knight);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
