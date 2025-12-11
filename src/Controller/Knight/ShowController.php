<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Knight;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    #[Route('/api/knight/{id}', name: 'knight_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        Knight $knight
    ): JsonResponse {
        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'inventory_item:read',
                'item:read'
            ]]),
            200
        );
    }
}
