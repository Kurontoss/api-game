<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;

final class DeleteController extends AbstractController
{
    #[Route('/api/enemy/{id}/delete', name: 'enemy_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        EnemyRepository $enemyRepo,
        Enemy $enemy
    ): JsonResponse {
        $enemyRepo->delete($enemy);

        return new JsonResponse(null, 204);
    }
}
