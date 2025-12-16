<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\EnemyRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EnemyRepository $enemyRepo,
    ) {}

    #[Route('/api/enemy/{id}', name: 'enemy_show', methods: ['GET'], requirements: ['id' => '\d+'])]
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
            200
        );
    }
}
