<?php

namespace App\Service\Validator\LootPool;

use App\DTO\ErrorDTO;
use App\Entity\LootPool;

class Validator
{
    public function validate(LootPool $lootPool): array
    {
        $errors = [];

        // Items, chances, minAmounts and maxAmounts are of equal length
        if (count($lootPool->getItems()) !== count($lootPool->getChances())) {
            $errors[] = new ErrorDTO(
                field: 'items',
                message: 'arrays items and chances are not of equal length'
            );
        }

        if (count($lootPool->getChances()) !== count($lootPool->getMinAmounts())) {
            $errors[] = new ErrorDTO(
                field: 'chances',
                message: 'arrays chances and minAmounts are not of equal length'
            );
        }

        if (count($lootPool->getMinAmounts()) !== count($lootPool->getMaxAmounts())) {
            $errors[] = new ErrorDTO(
                field: 'minAmounts',
                message: 'arrays minAmounts and maxAmounts are not of equal length'
            );
        }

        // Elements of chances add up to one
        if (abs(array_sum($lootPool->getChances()) - 1) > 0.00001) {
            $errors[] = new ErrorDTO(
                field: 'chances',
                message: 'Elements of array add up to ' . array_sum($lootPool->getChances()) . ' instead of 1'
            );
        }

        // Values in minAmounts < maxAmounts
        for ($i = 0; $i < min(count($lootPool->getMinAmounts()), count($lootPool->getMaxAmounts())); $i++) {
            if ($lootPool->getMinAmounts()[$i] > $lootPool->getMaxAmounts()[$i]) {
                $errors[] = new ErrorDTO(
                    field: "minAmounts[$i]",
                    message: 'The minimum value ' . $lootPool->getMinAmounts()[$i] . ' must be less than the maximum value ' . $lootPool->getMaxAmounts()[$i] . "at index $i."
                );
            }
        }

        return $errors;
    }
}