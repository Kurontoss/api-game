<?php

namespace App\Controller\LootPool;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\LootPoolAssembler;
use App\DTO\LootPool\CreateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\LootPool;
use App\Repository\LootPoolRepository;
use App\Service\ValidationService;
use App\Service\Validator\LootPool\CreateUpdateDTOValidator;
use App\Service\Validator\LootPool\Validator;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private Validator $validator,
        private CreateUpdateDTOValidator $createDTOValidator,
        private LootPoolRepository $lootPoolRepo,
        private LootPoolAssembler $assembler,
    ) {}

    #[OA\Post(
        summary: 'Create an loot pool',
        description: 'Creates a new loot pool. Requires admin privileges.',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Loot pool creation payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['loot_pool:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Loot pool successfully created',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: LootPool::class,
                        groups: ['loot_pool:read']
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_FORBIDDEN,
                description: 'Access denied',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/loot-pools', name: 'loot_pool_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json'
        );

        $errors = $this->validationService->validate($dto);
        $errors = array_merge($errors, $this->createDTOValidator->validate($dto));

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $lootPool = $this->assembler->fromCreateDTO($dto);

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
