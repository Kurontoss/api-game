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
        private SerializerInterface $serializer,
        private LootPoolRepository $lootPoolRepo,
    ) {}
    
    #[Route('/api/loot-pools', name: 'loot_pool_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $lootPools = $this->lootPoolRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($lootPools, 'json', ['groups' => [
                'loot_pool:read',
                'item:read'
            ]]),
            200
        );
    }
}
