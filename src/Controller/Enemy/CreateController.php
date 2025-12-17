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
use App\DTO\Enemy\CreateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Enemy;
use App\Repository\EnemyRepository;
use App\Service\ValidationService;
use App\Service\Validator\Enemy\CreateUpdateDTOValidator;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private CreateUpdateDTOValidator $createDTOValidator,
        private EnemyRepository $enemyRepo,
        private EnemyAssembler $assembler,
    ) {}

    #[OA\Post(
        summary: 'Create an enemy',
        description: 'Creates a new enemy. Requires admin privileges.',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Enemy creation payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['enemy:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Enemy successfully created',
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
    #[Route('/api/enemies', name: 'enemy_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json',
            ['groups' => ['enemy:write']]
        );

        $errors = $this->validationService->validate($dto);
        $errors = array_merge($errors, $this->createDTOValidator->validate($dto));

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $enemy = $this->assembler->fromCreateDTO($dto);

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
