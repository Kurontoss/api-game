<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\LootPoolRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}
    
    #[Route('/api/loot-pool', name: 'loot_pool_list', methods: ['GET'])]
    public function list(
        LootPoolRepository $lootPoolRepo
    ): JsonResponse {
        $lootPools = $lootPoolRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($lootPools, 'json', ['groups' => ['lootPool:read', 'item:read']]),
            200
        );
    }
}
