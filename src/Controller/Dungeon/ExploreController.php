<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\Dungeon\ExploreService;
use App\Service\Knight\LevelUpService;
use App\Entity\Knight;
use App\Entity\Dungeon;
use App\Repository\KnightRepository;
use App\Repository\DungeonRepository;

final class ExploreController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ExploreService $exploreService,
        private LevelUpService $levelUpService
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

        try {
            $battleSummary = $this->exploreService->explore($knight, $dungeon);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Your level is too low to enter this dungeon.');
        }

        $expGained = $battleSummary->expGained;
        $battle = $battleSummary->battle;

        $knight->setExp($knight->getExp() + $expGained);
        $this->levelUpService->levelUp($knight);

        $knightRepo->save($knight);

        return $this->json([
            'dungeon' => $this->serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'battle' => $this->serializer->normalize($battle, 'json', ['groups' => ['knight:read', 'enemy:read', 'battle:read']]),
            'expGained' => $expGained,
            'knight' => $this->serializer->normalize($knight, 'json',['groups' => ['knight:read']]),
        ], 200);
    }
}
