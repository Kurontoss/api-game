<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;

class BattleSummaryDTO
{
    #[Groups(['battle_summary:read'])]
    public array $fights;

    #[Groups(['battle_summary:read'])]
    public int $exp;

    #[Groups(['battle_summary:read'])]
    public array $items;
}