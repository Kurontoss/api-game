<?php

namespace App\Controller\Item;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\Item\EatDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Knight;
use App\Exception\ItemAmountTooLowException;
use App\Repository\Item\ItemInstanceRepository;
use App\Repository\KnightRepository;
use App\Service\Item\EatService;
use App\Service\ValidationService;
use App\Service\Validator\Item\EatDTOValidator;

final class EatController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private EatDTOValidator $eatDTOValidator,
        private EatService $eatService,
        private ItemInstanceRepository $itemInstanceRepo,
        private KnightRepository $knightRepo,
    ) {}

    #[OA\Post(
        summary: 'Eat an item',
        description: 'Specified knight eats the specified item and regenerates hp.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the item to eat',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Item eat payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: EatDTO::class,
                    groups: ['item_instance:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Item successfully eaten',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Knight::class
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
                response: JsonResponse::HTTP_BAD_REQUEST,
                description: 'Bad request',
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

        $response = $this->validationService->validate($dto);
        $response->errors = array_merge($response->errors, $this->eatDTOValidator->validate($dto));

        if (count($response->errors) > 0) {
            return new JsonResponse(
                $this->serializer->normalize($response, 'json'),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $itemInstance = $this->itemInstanceRepo->find($dto->itemInstanceId);
        $knight = $this->knightRepo->find($dto->knightId);

        if ($knight->getUser() !== $this->getUser()) {
            throw new AccessDeniedHttpException('The currently logged in user is not this knight\'s onwer');
        }

        if ($itemInstance->getKnight() !== $knight) {
            throw new AccessDeniedHttpException('This item doesn\'t belong to this knight');
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
