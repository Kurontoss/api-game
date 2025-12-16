<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\EnemyRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private EnemyRepository $enemyRepo,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/enemies/{id}', name: 'enemy_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $enemy = $this->enemyRepo->find($id);

        if (!$enemy) {
            throw new NotFoundHttpException('Enemy not found');
        }

        $this->enemyRepo->delete($enemy);

        return new JsonResponse(null, 204);
    }
}
