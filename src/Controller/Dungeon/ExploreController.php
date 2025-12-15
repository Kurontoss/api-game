<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\ValidationService;
use App\Service\Dungeon\ExploreService;
use App\Service\Knight\LevelUpService;
use App\Repository\DungeonRepository;
use App\Repository\KnightRepository;
use App\DTO\Dungeon\ExploreDTO;
use App\Exception\LevelTooLowException;

final class ExploreController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private ExploreService $exploreService,
        private LevelUpService $levelUpService,
        private DungeonRepository $dungeonRepo,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/dungeon/{id}/explore', name: 'dungeon_explore', methods: ['POST'])]
    public function create(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            ExploreDTO::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        $dto->dungeonId = $id;

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $dungeon = $this->dungeonRepo->find($dto->dungeonId);
        $knight = $this->knightRepo->find($dto->knightId);

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

        $this->knightRepo->save($knight);

        return $this->json([
            'dungeon' => $this->serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'fights' => $this->serializer->normalize($fights, 'json', ['groups' => ['fight:read', 'knight:read', 'enemy:read', 'inventory_item:read', 'item:read']]),
            'exp' => $exp,
            'items' => $this->serializer->normalize($items, 'json', ['groups' => ['inventory_item:read', 'item:read']]),
            'knight' => $this->serializer->normalize($knight, 'json', ['groups' => ['knight:read', 'knight_inventory:read', 'inventory_item:read', 'item:read']]),
        ], 200);
    }
}
