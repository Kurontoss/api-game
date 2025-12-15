<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Service\ValidationService;
use App\Service\Item\EatService;
use App\Repository\Item\InventoryItemRepository;
use App\Repository\KnightRepository;
use App\DTO\Item\EatDTO;
use App\Exception\ItemAmountTooLowException;

final class EatController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private EatService $eatService,
        private InventoryItemRepository $inventoryItemRepo,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/item/{id}/eat', name: 'item_eat', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function eat(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            EatDTO::class,
            'json',
            ['groups' => ['inventory_item:write']]
        );

        $dto->inventoryItemId = $id;

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $inventoryItem = $this->inventoryItemRepo->find($dto->inventoryItemId);
        $knight = $this->knightRepo->find($dto->knightId);

        if ($knight->getUser() !== $this->getUser()) {
            throw new BadRequestHttpException('The currently logged in user is not this knight\'s onwer!');
        }

        if ($inventoryItem->getKnight() !== $knight) {
            throw new BadRequestHttpException('This item doesn\'t belong to this knight!');
        }

        try {
            $this->eatService->eat($knight, $inventoryItem, $dto->amount);
        } catch (ItemAmountTooLowException $e) {
            throw new BadRequestHttpException('You don\'t have enough food to eat!');
        }

        $this->knightRepo->save($knight);

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
