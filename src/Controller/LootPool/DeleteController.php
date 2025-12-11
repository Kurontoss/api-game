<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\LootPoolRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private LootPoolRepository $lootPoolRepo,
    ) {}

    #[Route('/api/loot-pool/{id}/delete', name: 'loot_pool_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
    ): JsonResponse {
        $lootPool = $this->lootPoolRepo->find($id);
        $this->lootPoolRepo->delete($lootPool);

        return new JsonResponse(null, 204);
    }
}
