<?php

namespace App\DTO\Battle;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\Battle\FightDTO;
use App\Entity\Item\InventoryItem;

class BattleSummaryDTO
{
    #[Groups(['battle_summary:read'])]
    #[Assert\All([new Assert\Type(FightDTO::class)])]
    public array $fights = [];

    #[Groups(['battle_summary:read'])]
    #[Assert\PositiveOrZero]
    public int $exp = 0;

    #[Groups(['battle_summary:read'])]
    #[Assert\All([new Assert\Type(InventoryItem::class)])]
    public array $items = [];
}