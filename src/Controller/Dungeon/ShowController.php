<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Dungeon;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    #[Route('/api/dungeon/{id}', name: 'dungeon_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        Dungeon $dungeon
    ): JsonResponse {
        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => [
                'dungeon:read',
                'dungeon_enemies:read',
                'enemy:read'
            ]]),
            200
        );
    }
}
