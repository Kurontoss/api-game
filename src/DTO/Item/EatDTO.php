<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;

class EatDTO
{
    #[Groups(['inventory_item:write'])]
    public ?int $knightId;

    #[Groups(['inventory_item:write'])]
    public ?int $amount;
}