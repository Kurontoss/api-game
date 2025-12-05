<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Knight;

final class ShowController extends AbstractController
{
    #[Route('/api/knight/{id}', name: 'knight_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(SerializerInterface $serializer, Knight $knight): JsonResponse
    {
        return new JsonResponse(
            $serializer->normalize($knight, 'json', ['groups' => ['knight:read']]),
            200
        );
    }
}
