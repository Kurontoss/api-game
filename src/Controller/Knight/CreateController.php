<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Knight;
use App\Repository\KnightRepository;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/knight/create', name: 'knight_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $knight = $this->serializer->deserialize(
            $request->getContent(),
            Knight::class,
            'json',
            ['groups' => ['knight:write']]
        );

        $knight->setLevel(1);
        $knight->setExp(0);
        $knight->setExpToNextLevel(10);
        $knight->setHp(10);
        $knight->setMaxHp(10);
        $knight->setUser($this->getUser());

        $this->knightRepo->save($knight);

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'inventory_item:read',
                'item:read'
            ]]),
            201
        );
    }
}
