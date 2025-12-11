<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\Item\EatService;
use App\Repository\Item\InventoryItemRepository;
use App\Repository\KnightRepository;
use App\Entity\Knight;
use App\DTO\Item\EatDTO;
use App\Exception\ItemAmountTooLowException;

final class EatController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer
    ) {}

    #[Route('/api/knight/{id}/eat', name: 'item_eat', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function eat(
        Request $request,
        Knight $knight,
        EatService $eatService,
        InventoryItemRepository $inventoryItemRepo,
        KnightRepository $knightRepo,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            EatDTO::class,
            'json'
        );

        $item = $inventoryItemRepo->find($dto->inventoryItemId);

        if ($item === null) {
            throw new BadRequestHttpException('Invalid inventory item id!');
        }

        if ($dto->amount <= 0) {
            throw new BadRequestHttpException('Invalid amount!');
        }

        try {
            $eatService->eat($knight, $item, $dto->amount);
        } catch (ItemAmountTooLowException $e) {
            throw new BadRequestHttpException('You don\'t have enough food to eat!');
        }

        $knightRepo->save($knight);

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_inventory:read',
                'inventory_item:read',
                'item:read'
            ]]),
            200
        );
    }
}
