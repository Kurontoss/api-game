<?php

namespace App\Service\Validator\LootPool;

use App\DTO\ErrorDTO;
use App\DTO\LootPool\CreateDTO;
use App\DTO\LootPool\UpdateDTO;
use App\Repository\LootPoolRepository;

class CreateUpdateDTOValidator
{
    public function __construct(
        private LootPoolRepository $lootPoolRepo,
    ) {}

    public function validate(CreateDTO|UpdateDTO $dto): array
    {
        $errors = [];

        // Item entities exist in database
        for ($i = 0; $i < count($dto->items); $i++) {
            if (!$dto->items[$i]) {
                continue;
            }
            
            $item = $this->lootPoolRepo->find($dto->items[$i]);

            if (!$item) {
                $errors[] = new ErrorDTO(
                    field: "items[$i]",
                    message: 'Entity with index ' . $dto->items[$i] . ' does not exist.'
                );
            }
        }

        return $errors;
    }
}