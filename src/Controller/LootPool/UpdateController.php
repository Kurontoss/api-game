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
use App\DTO\LootPool\UpdateDTO;
use App\DTO\ResponseErrorDTO;
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

    #[OA\Patch(
        summary: 'Update a loot pool',
        description: 'Updates a loot pool. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the loot pool to update',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Loot pool update payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: UpdateDTO::class,
                    groups: ['loot_pool:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Loot pool successfully updated',
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
                response: JsonResponse::HTTP_NOT_FOUND,
                description: 'Not found',
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

        $response = $this->validationService->validate($dto);
        $response->errors = array_merge($response->errors, $this->updateDTOValidator->validate($dto));

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $lootPool = $this->lootPoolRepo->find($id);

        if (!$lootPool) {
            throw new NotFoundHttpException('Loot pool not found');
        }

        $lootPool = $this->assembler->fromUpdateDTO($dto, $lootPool);

        $response = $this->validator->validate($dto);

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
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
