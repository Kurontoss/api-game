<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ValidationService;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;
use App\Repository\DungeonRepository;
use App\Repository\LootPoolRepository;
use App\DTO\Enemy\CreateDTO;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private EnemyRepository $enemyRepo,
        private DungeonRepository $dungeonRepo,
        private LootPoolRepository $lootPoolRepo,
    ) {}

    #[Route('/api/enemy/create', name: 'enemy_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['enemy:write']]
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $dungeon = $this->dungeonRepo->find($dto->dungeonId);
        $lootPool = $this->lootPoolRepo->find($dto->lootPoolId);

        $enemy = new Enemy();
        $enemy->setName($dto->name);
        $enemy->setHp($dto->hp);
        $enemy->setStrength($dto->strength);
        $enemy->setExp($dto->exp);
        $enemy->setDungeon($dungeon);
        $enemy->setLootPool($lootPool);

        $this->enemyRepo->save($enemy);

        return new JsonResponse(
            $this->serializer->normalize($enemy, 'json', ['groups' => [
                'enemy:read',
                'enemy_dungeon:read',
                'dungeon:read',
                'enemy_loot_pool:read',
                'loot_pool:read',
                'item:read'
            ]]),
            201
        );
    }
}
