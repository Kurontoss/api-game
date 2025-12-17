<?php

namespace App\Controller\Enemy;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\EnemyAssembler;
use App\DTO\Enemy\UpdateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;
use App\Service\ValidationService;
use App\Service\Validator\Enemy\CreateUpdateDTOValidator;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private CreateUpdateDTOValidator $updateDTOValidator,
        private EnemyRepository $enemyRepo,
        private EnemyAssembler $assembler,
    ) {}

    #[OA\Patch(
        summary: 'Update an enemy',
        description: 'Updates an enemy. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the enemy to update',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Enemy update payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: UpdateDTO::class,
                    groups: ['enemy:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Enemy successfully updated',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Enemy::class,
                        groups: ['enemy:read']
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
    #[Route('/api/enemies/{id}', name: 'enemy_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json',
            ['groups' => ['enemy:write']]
        );

        $response = $this->validationService->validate($dto);
        $response->errors = array_merge($response->errors, $this->updateDTOValidator->validate($dto));

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $enemy = $this->enemyRepo->find($id);

        if (!$enemy) {
            throw new NotFoundHttpException('Enemy not found');
        }

        $enemy = $this->assembler->fromUpdateDTO($dto, $enemy);

        $this->enemyRepo->save($enemy);

        return new JsonResponse(
            $this->serializer->normalize($enemy, 'json', ['groups' => [
                'enemy:read',
                'enemy_dungeon:read',
                'dungeon:read',
                'enemy_loot_pool:read',
                'loot_pool:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_CREATED
        );
    }
}
