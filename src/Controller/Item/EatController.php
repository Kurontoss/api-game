<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\Item\EatDTO;
use App\Exception\ItemAmountTooLowException;
use App\Repository\Item\ItemInstanceRepository;
use App\Repository\KnightRepository;
use App\Service\Item\EatService;
use App\Service\ValidationService;

final class EatController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private EatService $eatService,
        private ItemInstanceRepository $itemInstanceRepo,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/items/{id}/eat', name: 'item_eat', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            EatDTO::class,
            'json',
            ['groups' => ['item_instance:write']]
        );

        $dto->itemInstanceId = $id;

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $itemInstance = $this->itemInstanceRepo->find($dto->itemInstanceId);
        $knight = $this->knightRepo->find($dto->knightId);

        if ($knight->getUser() !== $this->getUser()) {
            throw new AccessDeniedException('The currently logged in user is not this knight\'s onwer');
        }

        if ($itemInstance->getKnight() !== $knight) {
            throw new AccessDeniedException('This item doesn\'t belong to this knight');
        }

        try {
            $this->eatService->eat($knight, $itemInstance, $dto->amount);
        } catch (ItemAmountTooLowException $e) {
            throw new BadRequestHttpException('There is not enough food to eat');
        }

        $this->knightRepo->save($knight);

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_inventory:read',
                'item_instance:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
