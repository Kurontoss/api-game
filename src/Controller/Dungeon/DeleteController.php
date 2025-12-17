<?php

namespace App\Controller\Dungeon;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\DTO\ResponseErrorDTO;
use App\Repository\DungeonRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private DungeonRepository $dungeonRepo,
    ) {}

    #[OA\Delete(
        summary: 'Delete a dungeon',
        description: 'Deletes a dungeon with a given id. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the dungeon to delete',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_NO_CONTENT,
                description: 'Dungeon successfully deleted'
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
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/dungeons/{id}', name: 'dungeon_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $dungeon = $this->dungeonRepo->find($id);

        if (!$dungeon) {
            throw new NotFoundHttpException('Dungeon not found');
        }

        $this->dungeonRepo->delete($dungeon);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
