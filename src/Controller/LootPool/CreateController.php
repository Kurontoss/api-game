<?php

namespace App\Controller\LootPool;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\LootPoolAssembler;
use App\DTO\LootPool\CreateDTO;
use App\Entity\LootPool;
use App\Repository\Item\ItemRepository;
use App\Repository\LootPoolRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private LootPoolRepository $lootPoolRepo,
        private ItemRepository $itemRepo,
        private LootPoolAssembler $assembler,
    ) {}

    #[Route('/api/loot-pool/create', name: 'loot_pool_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json'
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $lootPool = $this->assembler->fromCreateDTO($dto);

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
