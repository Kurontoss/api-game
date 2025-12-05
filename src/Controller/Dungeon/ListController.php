<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\DungeonRepository;

final class ListController extends AbstractController
{
    #[Route('/api/dungeon', name: 'dungeon_list', methods: ['GET'])]
    public function list(SerializerInterface $serializer, DungeonRepository $repository): JsonResponse
    {
        $dungeons = $repository->findAll();

        return new JsonResponse(
            $serializer->normalize($dungeons, 'json', ['groups' => ['dungeon:read']]),
            200
        );
    }
}
