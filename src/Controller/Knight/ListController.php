<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\KnightRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    #[Route('/api/knight', name: 'knight_list', methods: ['GET'])]
    public function list(
        KnightRepository $knightRepo
    ): JsonResponse {
        $knights = $knightRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($knights, 'json', ['groups' => ['knight:read', 'user:read']]),
            200
        );
    }
}
