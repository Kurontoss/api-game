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
use App\DTO\Item\UpdateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Item\Item;
use App\Entity\Item\Food;
use App\Repository\Item\ItemRepository;
use App\Service\ValidationService;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private ItemRepository $itemRepo,
        private ItemAssembler $assembler,
    ) {}

    #[OA\Patch(
        summary: 'Update an item',
        description: 'Updates an item. Requires admin privileges.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the item to update',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Item update payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: UpdateDTO::class,
                    groups: ['item:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Item successfully updated',
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
                response: JsonResponse::HTTP_NOT_FOUND,
                description: 'Not found',
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
    #[Route('/api/items/{id}', name: 'item_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json'
        );

        $response = $this->validationService->validate($dto);

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $item = $this->itemRepo->find($id);

        if (!$item) {
            throw new NotFoundHttpException('Item not found');
        }

        $item = $this->assembler->fromUpdateDTO($dto, $item);

        $this->itemRepo->save($item);

        return new JsonResponse(
            $this->serializer->normalize($item, 'json', ['groups' => ['item:read']]),
            JsonResponse::HTTP_CREATED
        );
    }
}
