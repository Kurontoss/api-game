<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\KnightRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/knights/{id}', name: 'knight_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $knight = $this->knightRepo->find($id);

        if (!$knight) {
            throw new NotFoundHttpException('Knight not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'item_instance:read',
                'item:read'
            ]]),
            200
        );
    }
}
