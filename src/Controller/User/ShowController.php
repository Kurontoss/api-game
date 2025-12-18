<?php

namespace App\Controller\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\User;
use App\Repository\UserRepository;

final class ShowController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $userRepo,
    ) {}

    #[OA\Tag(name: 'Users')]
    #[OA\Get(
        summary: 'Show a user',
        description: 'Shows a user.',
        security: [['Bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'ID of the user to show',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'User successfully shown',
                content: new OA\JsonContent(
                    ref: new Model(
                        type: User::class,
                        groups: ['user:read']
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
            )
        ]
    )]
    #[Route('/api/users/{id}', name: 'user_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function __invoke(
        int $id,
    ): JsonResponse {
        $user = $this->userRepo->find($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return new JsonResponse(
            $this->serializer->normalize($user, 'json', ['groups' => [
                'user:read',
                'user_knights:read',
                'knight:read',
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
