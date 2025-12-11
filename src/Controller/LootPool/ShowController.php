<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\LootPool;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {}

    #[Route('/api/loot-pool/{id}', name: 'loot_pool_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        LootPool $lootPool,
    ): JsonResponse {
        return new JsonResponse(
            $this->serializer->normalize($lootPool, 'json', ['groups' => [
                'loot_pool:read',
                'item:read'
            ]]),
            200
        );
    }
}
