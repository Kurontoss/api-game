<?php

namespace App\DTO\Knight;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Enemy;
use App\Entity\Knight;

class BattleDTO
{
    #[Groups(['battle:read'])]
    public ?int $round = null;

    #[Groups(['battle:read'])]
    public ?Enemy $enemy = null;

    #[Groups(['battle:read'])]
    public ?Knight $knight = null;
}