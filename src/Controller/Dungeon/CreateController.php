<?php

namespace App\Controller\Dungeon;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\DungeonAssembler;
use App\DTO\Dungeon\CreateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private DungeonRepository $dungeonRepo,
        private DungeonAssembler $assembler,
    ) {}

    #[OA\Post(
        summary: 'Create a dungeon',
        description: 'Creates a new dungeon. Requires admin privileges.',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Dungeon creation payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['dungeon:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Dungeon successfully created',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Dungeon::class,
                        groups: ['dungeon:read']
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
                description: 'Access denied (ROLE_ADMIN required)',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
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

        $errors = $this->validationService->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dungeon = $this->assembler->fromCreateDTO($dto);

        $this->dungeonRepo->save($dungeon);

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            JsonResponse::HTTP_CREATED
        );
    }
}
