<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Enemy;

final class ShowController extends AbstractController
{
    #[Route('/api/enemy/{id}', name: 'enemy_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(SerializerInterface $serializer, Enemy $enemy): JsonResponse
    {
        return new JsonResponse(
            $serializer->normalize($enemy, 'json', ['groups' => ['enemy:read']]),
            200
        );
    }
}
