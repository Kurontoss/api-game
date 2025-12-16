<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\DungeonRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private DungeonRepository $dungeonRepo,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/dungeons/{id}', name: 'dungeon_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $dungeon = $this->dungeonRepo->find($id);

        if (!$dungeon) {
            throw new NotFoundHttpException('Dungeon not found');
        }

        $this->dungeonRepo->delete($dungeon);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
