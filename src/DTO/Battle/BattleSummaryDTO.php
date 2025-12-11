<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;

class BattleSummaryDTO
{
    #[Groups(['battle_summary:read'])]
    public ?array $fights = null;

    #[Groups(['battle_summary:read'])]
    public ?int $exp = null;

    #[Groups(['battle_summary:read'])]
    public ?array $items = null;
}