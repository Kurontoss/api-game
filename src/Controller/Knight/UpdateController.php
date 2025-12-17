<?php

namespace App\Controller\Knight;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

use App\Assembler\KnightAssembler;
use App\DTO\Knight\UpdateDTO;
use App\DTO\ResponseErrorDTO;
use App\Entity\Knight;
use App\Repository\KnightRepository;
use App\Service\ValidationService;

final class UpdateController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private KnightRepository $knightRepo,
        private KnightAssembler $assembler,
    ) {}

    #[OA\Patch(
        summary: 'Update a knight',
        description: 'Updates a knight. Requires the logged in user to be the same as the knight\'s user.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the knight to update',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Knight update payload',
            content: new OA\JsonContent(
                ref: new Model(
                    type: UpdateDTO::class,
                    groups: ['knight:write']
                )
            )
        ),
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Knight successfully updated',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: Knight::class,
                        groups: ['knight:read']
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
    #[Route('/api/knights/{id}', name: 'knight_update', methods: ['PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            UpdateDTO::class,
            'json',
            ['groups' => ['knight:write']]
        );

        $errors = $this->validationService->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $knight = $this->knightRepo->find($id);

        if (!$knight) {
            throw new NotFoundHttpException('Knight not found');
        }

        if ($this->getUser() !== $knight->getUser()) {
            throw new AccessDeniedException('Not authorized to delete this knight');
        }

        $knight = $this->assembler->fromUpdateDTO($dto, $knight);

        $this->knightRepo->save($knight);

        return new JsonResponse(
            $this->serializer->normalize($knight, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read',
                'knight_inventory:read',
                'item_instance:read',
                'item:read'
            ]]),
            JsonResponse::HTTP_CREATED
        );
    }
}
