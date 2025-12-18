<?php

namespace App\Controller\Dungeon;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\Dungeon;
use App\Repository\DungeonRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private DungeonRepository $dungeonRepo,
    ) {}

    #[OA\Tag(name: 'Dungeons')]
    #[OA\Get(
        summary: 'Show a dungeon',
        description: 'Shows a dungeon.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the dungeon to show',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Dungeon successfully shown',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Dungeon::class,
                        groups: ['dungeon:read']
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
            )
        ]
    )]
    #[Route('/api/dungeons/{id}', name: 'dungeon_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $dungeon = $this->dungeonRepo->find($id);

        if (!$dungeon) {
            throw new NotFoundHttpException('Dungeon not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($dungeon, 'json', ['groups' => [
                'dungeon:read',
                'dungeon_enemies:read',
                'enemy:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
