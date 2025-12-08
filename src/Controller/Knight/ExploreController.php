<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\Knight\ExploreService;
use App\Entity\Knight;
use App\Entity\Dungeon;
use App\Repository\KnightRepository;
use App\Repository\DungeonRepository;

final class ExploreController extends AbstractController
{
    #[Route('/api/knight/{knightId}/explore/{dungeonId}', name: 'knight_explore', methods: ['POST'])]
    public function create(
        SerializerInterface $serializer,
        ExploreService $exploreService,
        KnightRepository $knightRepository,
        DungeonRepository $dungeonRepository,
        int $knightId,
        int $dungeonId
    ): JsonResponse
    {
        $knight = $knightRepository->find($knightId);
        $dungeon = $dungeonRepository->find($dungeonId);

        $battle = $exploreService->explore($knight, $dungeon);

        $knightRepository->save($knight);

        return $this->json([
            'dungeon' => $serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'battle' => $serializer->normalize($battle, 'json',['groups' => ['knight:read', 'enemy:read']]),
            'knight' => $serializer->normalize($knight, 'json',['groups' => ['knight:read']]),
        ], 200);
    }
}
