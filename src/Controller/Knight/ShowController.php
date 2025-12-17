<?php

namespace App\Controller\Knight;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\Knight;
use App\Repository\KnightRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private KnightRepository $knightRepo,
    ) {}

    #[OA\Get(
        summary: 'Show a knight',
        description: 'Shows a knight.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the knight to show',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Knight successfully shown',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Knight::class,
                        groups: ['knight:read']
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
    #[Route('/api/knights/{id}', name: 'knight_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $knight = $this->knightRepo->find($id);

        if (!$knight) {
            throw new NotFoundHttpException('Knight not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'item_instance:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
