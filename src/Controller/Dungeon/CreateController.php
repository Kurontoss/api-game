<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\DungeonAssembler;
use App\DTO\Dungeon\CreateDTO;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private DungeonRepository $dungeonRepo,
        private DungeonAssembler $assembler,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/dungeons', name: 'dungeon_create', methods: ['POST'])]
    public function __invoke(
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

        $dungeon = $this->assembler->fromCreateDTO($dto);

        $this->dungeonRepo->save($dungeon);

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            201
        );
    }
}
