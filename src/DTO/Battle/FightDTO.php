<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Enemy;
use App\Entity\Knight;
use App\Entity\Item\InventoryItem;

class FightDTO
{
    #[Groups(['fight:read'])]
    public int $index;

    #[Groups(['fight:read'])]
    public ?Enemy $enemy;

    #[Groups(['fight:read'])]
    public ?Knight $knight;

    #[Groups(['fight:read'])]
    public int $exp;

    #[Groups(['fight:read'])]
    public ?InventoryItem $item;

    #[Groups(['fight:read'])]
    public bool $isWon;
}