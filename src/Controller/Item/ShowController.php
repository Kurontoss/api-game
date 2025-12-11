<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\Item\ItemRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ItemRepository $itemRepo,
    ) {}

    #[Route('/api/item/{id}', name: 'item_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(
        int $id,
    ): JsonResponse {
        $item = $this->itemRepo->find($id);

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            200
        );
    }
}
