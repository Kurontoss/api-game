<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Enemy;
use App\Entity\Knight;
use App\Entity\Item\InventoryItem;

class FightDTO
{
    #[Groups(['fight:read'])]
    public int $index = 0;

    #[Groups(['fight:read'])]
    public ?Enemy $enemy = null;

    #[Groups(['fight:read'])]
    public ?Knight $knight = null;

    #[Groups(['fight:read'])]
    public int $exp = 0;

    #[Groups(['fight:read'])]
    public ?InventoryItem $item = null;

    #[Groups(['fight:read'])]
    public bool $isWon = false;
}