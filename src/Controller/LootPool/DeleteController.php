<?php

namespace App\Controller\LootPool;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\DTO\ResponseErrorDTO;
use App\Repository\LootPoolRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private LootPoolRepository $lootPoolRepo,
    ) {}

    #[OA\Tag(name: 'Loot Pools')]
    #[OA\Delete(
        summary: 'Delete a loot pool',
        description: 'Deletes a loot pool with a given id. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the loot pool to delete',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_NO_CONTENT,
                description: 'Loot pool successfully deleted'
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
    #[Route('/api/loot-pools/{id}', name: 'loot_pool_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $lootPool = $this->lootPoolRepo->find($id);

        if (!$lootPool) {
            throw new NotFoundHttpException('Loot pool not found');
        }

        $this->lootPoolRepo->delete($lootPool);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
