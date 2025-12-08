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
    public function __construct(
        private SerializerInterface $serializer,
        private ExploreService $exploreService
    ) {}

    #[Route('/api/knight/{knightId}/explore/{dungeonId}', name: 'knight_explore', methods: ['POST'])]
    public function create(
        KnightRepository $knightRepo,
        DungeonRepository $dungeonRepo,
        int $knightId,
        int $dungeonId
    ): JsonResponse {
        $knight = $knightRepo->find($knightId);
        $dungeon = $dungeonRepo->find($dungeonId);

        $battle = $this->exploreService->explore($knight, $dungeon);

        $knightRepo->save($knight);

        return $this->json([
            'dungeon' => $this->serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'battle' => $this->serializer->normalize($battle, 'json',['groups' => ['knight:read', 'enemy:read']]),
            'knight' => $this->serializer->normalize($knight, 'json',['groups' => ['knight:read']]),
        ], 200);
    }
}
