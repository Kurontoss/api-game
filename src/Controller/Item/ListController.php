<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\Item\ItemRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ItemRepository $itemRepo,
    ) {}

    #[Route('/api/item', name: 'item_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $items = $this->itemRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($items, 'json', ['groups' => ['item:read']]),
            200
        );
    }
}
