<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\DungeonRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}
    
    #[Route('/api/dungeon', name: 'dungeon_list', methods: ['GET'])]
    public function list(
        DungeonRepository $dungeonRepo
    ): JsonResponse {
        $dungeons = $dungeonRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($dungeons, 'json', ['groups' => ['dungeon:read']]),
            200
        );
    }
}
