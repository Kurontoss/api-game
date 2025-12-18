<?php

namespace App\Controller\Dungeon;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\DungeonAssembler;
use App\DTO\Dungeon\UpdateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;
use App\Service\ValidationService;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private DungeonRepository $dungeonRepo,
        private DungeonAssembler $assembler,
    ) {}

    #[OA\Tag(name: 'Dungeons')]
    #[OA\Patch(
        summary: 'Update a dungeon',
        description: 'Updates a dungeon. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the dungeon to update',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Dungeon update payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: UpdateDTO::class,
                    groups: ['dungeon:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Dungeon successfully updated',
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
    #[Route('/api/dungeons/{id}', name: 'dungeon_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        $response = $this->validationService->validate($dto);

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $dungeon = $this->dungeonRepo->find($id);

        if (!$dungeon) {
            throw new NotFoundHttpException('Dungeon not found');
        }

        $dungeon = $this->assembler->fromUpdateDTO($dto, $dungeon);

        $this->dungeonRepo->save($dungeon);

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => ['dungeon:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
