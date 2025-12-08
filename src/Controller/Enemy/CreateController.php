<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;
use App\Repository\DungeonRepository;
use App\DTO\Enemy\EnemyCreateDTO;

final class CreateController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    #[Route('/api/enemy/create', name: 'enemy_create', methods: ['POST'])]
    public function create(
        Request $request,
        EnemyRepository $enemyRepository,
        DungeonRepository $dungeonRepository
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            EnemyCreateDTO::class,
            'json'
        );

        $dungeon = $dungeonRepository->find($dto->dungeonId);

        $enemy = new Enemy();
        $enemy->setName($dto->name);
        $enemy->setHp($dto->hp);
        $enemy->setStrength($dto->strength);
        $enemy->setExp($dto->exp);
        $enemy->setDungeon($dungeon);

        $enemyRepository->save($enemy);

        return new JsonResponse(
            $this->serializer->normalize($enemy, 'json', ['groups' => ['enemy:read']]),
            201
        );
    }
}
