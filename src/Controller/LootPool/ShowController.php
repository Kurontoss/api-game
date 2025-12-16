<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\LootPoolRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private LootPoolRepository $lootPoolRepo,
    ) {}

    #[Route('/api/loot-pools/{id}', name: 'loot_pool_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $lootPool = $this->lootPoolRepo->find($id);

        if (!$lootPool) {
            throw new NotFoundHttpException('Loot pool not found');
        }
        
        return new JsonResponse(
            $this->serializer->normalize($lootPool, 'json', ['groups' => [
                'loot_pool:read',
                'item:read'
            ]]),
            200
        );
    }
}
