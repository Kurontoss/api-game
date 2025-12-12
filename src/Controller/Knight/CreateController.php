<?php

namespace App\Controller\Knight;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ValidationService;
use App\Entity\Knight;
use App\DTO\Knight\CreateDTO;
use App\Repository\KnightRepository;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/knight/create', name: 'knight_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['knight:write']]
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $knight = new Knight();
        $knight->setName($dto->name);
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
