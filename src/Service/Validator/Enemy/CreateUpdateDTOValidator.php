<?php

namespace App\Service\Validator\Enemy;

use App\DTO\Enemy\CreateDTO;
use App\DTO\Enemy\UpdateDTO;
use App\DTO\ErrorDTO;
use App\Repository\DungeonRepository;
use App\Repository\LootPoolRepository;

class CreateUpdateDTOValidator
{
    public function __construct(
        private DungeonRepository $dungeonRepo,
        private LootPoolRepository $lootPoolRepo,
    ) {}
    
    public function validate(CreateDTO|UpdateDTO $dto): array
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

        if ($dto->lootPoolId) {
            $lootPool = $this->lootPoolRepo->find($dto->lootPoolId);

            if (!$lootPool) {
                $errors[] = new ErrorDTO(
                    field: "lootPoolId",
                    message: 'Entity with index ' . $dto->lootPoolId . ' does not exist.'
                );
            }
        }

        return $errors;
    }
}