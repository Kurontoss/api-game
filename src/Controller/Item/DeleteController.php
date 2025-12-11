<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Item\Item;
use App\Repository\Item\ItemRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private ItemRepository $itemRepo,
    ) {}

    #[Route('/api/item/{id}/delete', name: 'item_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        Item $item,
    ): JsonResponse {
        $this->itemRepo->delete($item);

        return new JsonResponse(null, 204);
    }
}
