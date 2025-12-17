<?php

namespace App\Controller\Enemy;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EnemyRepository $enemyRepo,
    ) {}

    #[OA\Get(
        summary: 'Show an enemy',
        description: 'Shows an enemy.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the enemy to show',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Enemy successfully shown',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Enemy::class,
                        groups: ['enemy:read']
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_NOT_FOUND,
                description: 'Enemy not found',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
    #[Route('/api/enemies/{id}', name: 'enemy_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $enemy = $this->enemyRepo->find($id);

        if (!$enemy) {
            throw new NotFoundHttpException('Enemy not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($enemy, 'json', ['groups' => [
                'enemy:read',
                'enemy_dungeon:read',
                'dungeon:read',
                'enemy_loot_pool:read',
                'loot_pool:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
