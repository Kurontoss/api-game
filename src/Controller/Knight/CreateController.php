<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Knight;
use App\Repository\KnightRepository;

final class CreateController extends AbstractController
{
    #[Route('/api/knight/create', name: 'knight_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, KnightRepository $repository): JsonResponse
    {
        $knight = $serializer->deserialize(
            $request->getContent(),
            Knight::class,
            'json',
            ['groups' => ['knight:write']]
        );

        $knight->setLevel(1);

        $repository->save($knight);

        return new JsonResponse(
            $serializer->normalize($knight, 'json', ['groups' => ['knight:read']]),
            201
        );
    }
}
