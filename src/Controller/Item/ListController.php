<?php

namespace App\Controller\Item;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Item\Item;
use App\Repository\Item\ItemRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ItemRepository $itemRepo,
    ) {}

    #[OA\Tag(name: 'Items')]
    #[OA\Get(
        summary: 'List all items',
        description: 'Lists all items.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Item list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: Item::class,
                            groups: ['item:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/items', name: 'item_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $items = $this->itemRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($items, 'json', ['groups' => ['item:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
