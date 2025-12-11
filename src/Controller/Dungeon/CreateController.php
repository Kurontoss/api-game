<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private DungeonRepository $dungeonRepo,
    ) {}

    #[Route('/api/dungeon/create', name: 'dungeon_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dungeon = $this->serializer->deserialize(
            $request->getContent(),
            Dungeon::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        $this->dungeonRepo->save($dungeon);

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            201
        );
    }
}
