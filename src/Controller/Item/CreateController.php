<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\Item\CreateDTO;
use App\Entity\Item\Item;
use App\Entity\Item\Food;
use App\Repository\Item\ItemRepository;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ItemRepository $itemRepo,
    ) {}

    #[Route('/api/item/create', name: 'item_create', methods: ['POST'])]
    public function create(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json'
        );

        if ($dto->type == 'food') {
            $item = new Food();
            $item->setHpRegen($dto->hpRegen);
        } else {
            $item = new Item();
        }

        $item->setName($dto->name);
        $item->setValue($dto->value);

        $this->itemRepo->save($item);

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            201
        );
    }
}
