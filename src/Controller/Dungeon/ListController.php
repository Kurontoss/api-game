<?php

namespace App\Controller\Dungeon;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Dungeon;
use App\Repository\DungeonRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private DungeonRepository $dungeonRepo,
    ) {}
    
    #[OA\Get(
        summary: 'List all dungeons',
        description: 'Lists all dungeons.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Dungeon list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: Dungeon::class,
                            groups: ['dungeon:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/dungeons', name: 'dungeon_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $dungeons = $this->dungeonRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($dungeons, 'json', ['groups' => ['dungeon:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
