<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\DungeonRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private DungeonRepository $dungeonRepo,
    ) {}

    #[Route('/api/dungeon/{id}', name: 'dungeon_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        int $id,
    ): JsonResponse {
        $dungeon = $this->dungeonRepo->find($id);

        if (!$dungeon) {
            throw new NotFoundHttpException('Dungeon not found');
        }

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
