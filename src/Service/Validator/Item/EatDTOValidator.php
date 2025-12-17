<?php

namespace App\Service\Validator\Item;

use App\DTO\ErrorDTO;
use App\DTO\Item\EatDTO;
use App\Repository\Item\ItemInstanceRepository;
use App\Repository\KnightRepository;

class EatDTOValidator
{
    public function __construct(
        private KnightRepository $knightRepo,
        private ItemInstanceRepository $itemInstanceRepo,
    ) {}
    
    public function validate(EatDTO $dto): array
    {
        $errors = [];

        if ($dto->knightId) {
            $knight = $this->knightRepo->find($dto->knightId);

            if (!$knight) {
                $errors[] = new ErrorDTO(
                    field: "knightId",
                    message: 'Entity with index ' . $dto->knightId . ' does not exist.'
                );
            }
        }
        
        if ($dto->itemInstanceId) {
            $itemInstance = $this->itemInstanceRepo->find($dto->itemInstanceId);

            if (!$itemInstance) {
                $errors[] = new ErrorDTO(
                    field: "itemInstanceId",
                    message: 'Entity with index ' . $dto->itemInstanceId . ' does not exist.'
                );
            }
        }

        return $errors;
    }
}