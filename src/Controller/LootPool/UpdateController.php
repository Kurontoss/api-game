<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\LootPoolAssembler;
use App\DTO\LootPool\UpdateDTO;
use App\Entity\LootPool;
use App\Repository\LootPoolRepository;
use App\Service\ValidationService;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private LootPoolRepository $lootPoolRepo,
        private LootPoolAssembler $assembler,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/loot-pools/{id}', name: 'loot_pool_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json'
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $lootPool = $this->lootPoolRepo->find($id);

        $lootPool = $this->assembler->fromUpdateDTO($dto, $lootPool);

        $this->lootPoolRepo->save($lootPool);

        return new JsonResponse(
            $this->serializer->normalize($lootPool, 'json', ['groups' => [
                'loot_pool:read',
                'item:read',
            ]]),
            201
        );
    }
}
