<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\KnightRepository;

final class ListController extends AbstractController
{
    #[Route('/api/knight', name: 'knight_list', methods: ['GET'])]
    public function list(SerializerInterface $serializer, KnightRepository $repository): JsonResponse
    {
        $knights = $repository->findAll();

        return new JsonResponse(
            $serializer->normalize($knights, 'json', ['groups' => ['knight:read']]),
            200
        );
    }
}
