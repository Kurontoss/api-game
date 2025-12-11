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
use App\Exception\LevelTooLowException;

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

        if ($knight->getUser() !== $this->getUser()) {
            throw new BadRequestHttpException('The currently logged in user is not this knight\'s onwer!');
        }

        try {
            $battleSummary = $this->exploreService->explore($knight, $dungeon);
        } catch (LevelTooLowException $e) {
            throw new BadRequestHttpException('Your level is too low to enter this dungeon.');
        }

        $exp = $battleSummary->exp;
        $fights = $battleSummary->fights;
        $items = $battleSummary->items;

        $this->levelUpService->levelUp($knight);

        $knightRepo->save($knight);

        return $this->json([
            'dungeon' => $this->serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'fights' => $this->serializer->normalize($fights, 'json', ['groups' => ['fight:read', 'knight:read', 'enemy:read', 'inventory_item:read', 'item:read']]),
            'exp' => $exp,
            'items' => $this->serializer->normalize($items, 'json', ['groups' => ['inventory_item:read', 'item:read']]),
            'knight' => $this->serializer->normalize($knight, 'json', ['groups' => ['knight:read', 'knight_inventory:read', 'inventory_item:read', 'item:read']]),
        ], 200);
    }
}
