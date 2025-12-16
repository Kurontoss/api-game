<?php

namespace App\Controller\Item;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\ItemAssembler;
use App\DTO\Item\CreateDTO;
use App\Entity\Item\Item;
use App\Entity\Item\Food;
use App\Repository\Item\ItemRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validator,
        private ItemRepository $itemRepo,
        private ItemAssembler $assembler,
    ) {}

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/items', name: 'item_create', methods: ['POST'])]
    public function __invoke(
        Request $request,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CreateDTO::class,
            'json'
        );

        if ($errors = $this->validator->validate($dto)) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], 422);
        }

        $item = $this->assembler->fromCreateDTO($dto);

        $this->itemRepo->save($item);

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            201
        );
    }
}
