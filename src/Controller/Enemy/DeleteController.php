<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private EnemyRepository $enemyRepo,
    ) {}

    #[Route('/api/enemy/{id}/delete', name: 'enemy_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        Enemy $enemy,
    ): JsonResponse {
        $this->enemyRepo->delete($enemy);

        return new JsonResponse(null, 204);
    }
}
