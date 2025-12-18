<?php

namespace App\Controller\Item;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\Item\Item;
use App\Repository\Item\ItemRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ItemRepository $itemRepo,
    ) {}

    #[OA\Tag(name: 'Items')]
    #[OA\Get(
        summary: 'Show an item',
        description: 'Shows an item.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the item to show',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Item successfully shown',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Item::class,
                        groups: ['item:read']
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
    #[Route('/api/items/{id}', name: 'item_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $item = $this->itemRepo->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
