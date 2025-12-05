<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;

final class CreateController extends AbstractController
{
    #[Route('/api/enemy/create', name: 'enemy_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EnemyRepository $repository): JsonResponse
    {
        $enemy = $serializer->deserialize(
            $request->getContent(),
            Enemy::class,
            'json',
            ['groups' => ['enemy:write']]
        );

        $repository->save($enemy);

        return new JsonResponse(
            $serializer->normalize($enemy, 'json', ['groups' => ['enemy:read']]),
            201
        );
    }
}
