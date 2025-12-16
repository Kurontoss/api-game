<?php

namespace App\Controller\Enemy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\EnemyRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EnemyRepository $enemyRepo,
    ) {}

    #[Route('/api/enemy', name: 'enemy_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $enemies = $this->enemyRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($enemies, 'json', ['groups' => ['enemy:read']]),
            200
        );
    }
}
