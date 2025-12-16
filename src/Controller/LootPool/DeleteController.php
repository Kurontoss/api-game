<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\LootPoolRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private LootPoolRepository $lootPoolRepo,
    ) {}

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
