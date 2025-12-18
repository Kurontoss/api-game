<?php

namespace App\Controller\Item;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\ItemAssembler;
use App\DTO\Item\CreateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Item\Item;
use App\Entity\Item\Food;
use App\Repository\Item\ItemRepository;
use App\Service\ValidationService;

final class CreateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private ItemRepository $itemRepo,
        private ItemAssembler $assembler,
    ) {}

    #[OA\Tag(name: 'Items')]
    #[OA\Post(
        summary: 'Create an item',
        description: 'Creates a new item. Requires admin privileges.',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Item creation payload. Type should be either "food" or blank.',
            content: new OA\JsonContent(
                ref: new Model(
                    type: CreateDTO::class,
                    groups: ['item:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Item successfully created',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Item::class,
                        groups: ['item:read']
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            ),
            new OA\Response(
                response: JsonResponse::HTTP_FORBIDDEN,
                description: 'Access denied',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: ResponseErrorDTO::class
                    )
                )
            )
        ]
    )]
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

        $response = $this->validationService->validate($dto);

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $item = $this->assembler->fromCreateDTO($dto);

        $this->itemRepo->save($item);

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            JsonResponse::HTTP_CREATED
        );
    }
}
