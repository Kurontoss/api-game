<?php

namespace App\Controller\Knight;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Knight;
use App\Repository\KnightRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private KnightRepository $knightRepo,
    ) {}

    #[OA\Tag(name: 'Knights')]
    #[OA\Get(
        summary: 'List all knights',
        description: 'Lists all knights.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Knight list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: Knight::class,
                            groups: ['knight:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/knights', name: 'knight_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $knights = $this->knightRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($knights, 'json', ['groups' => [
                'knight:read',
                'knight_user:read',
                'user:read'
            ]]),
            JsonResponse::HTTP_OK
        );
    }
}
