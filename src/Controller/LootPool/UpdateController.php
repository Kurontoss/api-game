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
use App\Service\Validator\LootPool\CreateUpdateDTOValidator;
use App\Service\Validator\LootPool\Validator;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private Validator $validator,
        private CreateUpdateDTOValidator $updateDTOValidator,
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

        $errors = $this->validationService->validate($dto);
        $errors = array_merge($errors, $this->updateDTOValidator->validate($dto));

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $lootPool = $this->lootPoolRepo->find($id);

        $lootPool = $this->assembler->fromUpdateDTO($dto, $lootPool);

        $errors = $this->validator->validate($lootPool);

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error (entity)',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->lootPoolRepo->save($lootPool);

        return new JsonResponse(
            $this->serializer->normalize($lootPool, 'json', ['groups' => [
                'loot_pool:read',
                'item:read',
            ]]),
            JsonResponse::HTTP_CREATED
        );
    }
}
