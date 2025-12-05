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
    #[Route('/dungeon/create', name: 'dungeon_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, DungeonRepository $repository): JsonResponse
    {
        $dungeon = $serializer->deserialize(
            $request->getContent(),
            Dungeon::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        $repository->save($dungeon);

        return $this->json(
            $serializer->serialize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            201
        );
    }
}
