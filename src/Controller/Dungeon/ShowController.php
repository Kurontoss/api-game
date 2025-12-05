<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Dungeon;

final class ShowController extends AbstractController
{
    #[Route('/api/dungeon/{id}', name: 'dungeon_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(SerializerInterface $serializer, Dungeon $dungeon): JsonResponse
    {
        return new JsonResponse(
            $serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            200
        );
    }
}
