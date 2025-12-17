<?php

namespace App\Controller\LootPool;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\LootPool;
use App\Repository\LootPoolRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private LootPoolRepository $lootPoolRepo,
    ) {}
    
    #[OA\Get(
        summary: 'List all loot pools',
        description: 'Lists all loot pools.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Loot pools list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: LootPool::class,
                            groups: ['loot_pool:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/loot-pools', name: 'loot_pool_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $lootPools = $this->lootPoolRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($lootPools, 'json', ['groups' => [
                'loot_pool:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
