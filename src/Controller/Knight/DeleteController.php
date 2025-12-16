<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\Repository\KnightRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/knights/{id}', name: 'knight_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $knight = $this->knightRepo->find($id);

        if ($this->getUser() !== $knight->getUser()) {
            throw new AccessDeniedException('Not authorized to delete this knight');
        }

        if (!$knight) {
            throw new NotFoundHttpException('Knight not found');
        }

        $this->knightRepo->delete($knight);

        return new JsonResponse(null, 204);
    }
}
