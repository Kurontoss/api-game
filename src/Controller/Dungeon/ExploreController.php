<?php

namespace App\Controller\Dungeon;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\SerializerInterface;

use App\DTO\Dungeon\ExploreDTO;
use App\Exception\LevelTooLowException;
use App\Repository\DungeonRepository;
use App\Repository\KnightRepository;
use App\Service\Dungeon\ExploreService;
use App\Service\Knight\LevelUpService;
use App\Service\ValidationService;
use App\Service\Validator\Dungeon\ExploreDTOValidator;

final class ExploreController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidationService $validationService,
        private ExploreDTOValidator $exploreDTOValidator,
        private ExploreService $exploreService,
        private LevelUpService $levelUpService,
        private DungeonRepository $dungeonRepo,
        private KnightRepository $knightRepo,
    ) {}

    #[Route('/api/dungeons/{id}/explore', name: 'dungeon_explore', methods: ['POST'])]
    public function __invoke(
        Request $request,
        int $id,
    ): JsonResponse {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            ExploreDTO::class,
            'json',
            ['groups' => ['dungeon:write']]
        );

        $dto->dungeonId = $id;

        $errors = $this->validationService->validate($dto);
        $errors = array_merge($errors, $this->exploreDTOValidator->validate($dto));

        if (count($errors) > 0) {
            return new JsonResponse([
                'reason' => 'Validation error',
                'errors' => $errors
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dungeon = $this->dungeonRepo->find($dto->dungeonId);
        $knight = $this->knightRepo->find($dto->knightId);

        if ($knight->getUser() !== $this->getUser()) {
            throw new AccessDeniedExceptionHttpException('The currently logged in user is not this knight\'s onwer');
        }

        try {
            $battleSummary = $this->exploreService->explore($knight, $dungeon);
        } catch (LevelTooLowException $e) {
            throw new BadRequestHttpException('Your level is too low to enter this dungeon');
        }

        $exp = $battleSummary->exp;
        $fights = $battleSummary->fights;
        $items = $battleSummary->items;

        $this->levelUpService->levelUp($knight);

        $this->knightRepo->save($knight);

        return $this->json([
            'dungeon' => $this->serializer->normalize($dungeon, 'json',['groups' => ['dungeon:read']]),
            'fights' => $this->serializer->normalize($fights, 'json', ['groups' => ['fight:read', 'knight:read', 'enemy:read', 'item_instance:read', 'item:read']]),
            'exp' => $exp,
            'items' => $this->serializer->normalize($items, 'json', ['groups' => ['item_instance:read', 'item:read']]),
            'knight' => $this->serializer->normalize($knight, 'json', ['groups' => ['knight:read', 'knight_inventory:read', 'item_instance:read', 'item:read']]),
        ], JsonResponse::HTTP_OK);
    }
}
