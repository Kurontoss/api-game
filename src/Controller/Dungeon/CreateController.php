<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\ValidationService;
use App\Entity\Dungeon;
use App\DTO\Dungeon\CreateDTO;
use App\Repository\DungeonRepository;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private DungeonRepository $dungeonRepo,
    ) {}

    #[Route('/api/dungeon/create', name: 'dungeon_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $dungeon = new Dungeon();
        $dungeon->setName($dto->name);
        $dungeon->setLevel($dto->level);
        $dungeon->setExp($dto->exp);

        $this->dungeonRepo->save($dungeon);

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            201
        );
    }
}
