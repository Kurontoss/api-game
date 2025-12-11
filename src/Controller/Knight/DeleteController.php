<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Knight;
use App\Repository\KnightRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/knight/{id}/delete', name: 'knight_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        Knight $knight,
    ): JsonResponse {
        $this->knightRepo->delete($knight);

        return new JsonResponse(null, 204);
    }
}
