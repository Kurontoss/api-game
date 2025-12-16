<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use App\Repository\Item\ItemRepository;

final class DeleteController extends AbstractController
{
    public function __construct(
        private ItemRepository $itemRepo,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/items/{id}', name: 'item_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $item = $this->itemRepo->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        $this->itemRepo->delete($item);

        return new JsonResponse(null, 204);
    }
}
