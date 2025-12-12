<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class EatDTO
{
    #[Groups(['inventory_item:write'])]
    #[Assert\NotNull]
    #[Assert\Positive]
    public ?int $knightId = null;

    #[Groups(['inventory_item:write'])]
    #[Assert\Positive]
    public int $amount = 1;
}