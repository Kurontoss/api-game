<?php

namespace App\Controller\User;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\User;
use App\Repository\UserRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private UserRepository $userRepo,
    ) {}
    
    #[OA\Tag(name: 'Users')]
    #[OA\Get(
        summary: 'List all users',
        description: 'Lists all users.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'User list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: User::class,
                            groups: ['user:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/users', name: 'user_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $users = $this->userRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($users, 'json', ['groups' => ['user:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
