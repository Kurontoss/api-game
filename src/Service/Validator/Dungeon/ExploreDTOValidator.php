<?php

namespace App\Service\Validator\Dungeon;

use App\DTO\ErrorDTO;
use App\DTO\Dungeon\ExploreDTO;
use App\Repository\DungeonRepository;
use App\Repository\KnightRepository;

class ExploreDTOValidator
{
    public function __construct(
        private DungeonRepository $dungeonRepo,
        private KnightRepository $knightRepo,
    ) {}
    
    public function validate(ExploreDTO $dto): array
    {
        $errors = [];

        if ($dto->dungeonId) {
            $dungeon = $this->dungeonRepo->find($dto->dungeonId);

            if (!$dungeon) {
                $errors[] = new ErrorDTO(
                    field: "dungeonId",
                    message: 'Entity with index ' . $dto->dungeonId . ' does not exist.'
                );
            }        
        }

        if ($dto->knightId) {
            $knight = $this->knightRepo->find($dto->knightId);

            if (!$knight) {
                $errors[] = new ErrorDTO(
                    field: "knightId",
                    message: 'Entity with index ' . $dto->knightId . ' does not exist.'
                );
            }        
        }

        return $errors;
    }
}