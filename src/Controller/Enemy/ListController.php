<?php

namespace App\Controller\Enemy;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Entity\Enemy;
use App\Repository\EnemyRepository;

final class ListController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private EnemyRepository $enemyRepo,
    ) {}

    #[OA\Get(
        summary: 'List all enemies',
        description: 'Lists all enemies.',
        security: [['Bearer' => []]],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Enemy list successfully shown',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        ref: new Model(
                            type: Enemy::class,
                            groups: ['enemy:read']
                        )
                    )
                )
            )
        ]
    )]
    #[Route('/api/enemies', name: 'enemy_list', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $enemies = $this->enemyRepo->findAll();

        return new JsonResponse(
            $this->serializer->normalize($enemies, 'json', ['groups' => ['enemy:read']]),
            JsonResponse::HTTP_OK
        );
    }
}
