<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;

final class DeleteController extends AbstractController
{
    #[Route('/api/dungeon/{id}/delete', name: 'dungeon_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function create(DungeonRepository $repository, Dungeon $dungeon): JsonResponse
    {
        $repository->delete($dungeon);

        return new JsonResponse(null, 204);
    }
}
