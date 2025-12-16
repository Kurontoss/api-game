<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\Enemy;
use App\Entity\Item\ItemInstance;
use App\Entity\Knight;

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
    public ?ItemInstance $item;

    #[Groups(['fight:read'])]
    public bool $isWon;
}