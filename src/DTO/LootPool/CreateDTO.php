<?php

namespace App\DTO\LootPool;

use Symfony\Component\Serializer\Annotation\Groups;

class CreateDTO
{
    #[Groups(['loot_pool:write'])]
    public ?string $name = null;

    #[Groups(['loot_pool:write'])]
    public array $items = [];
    
    #[Groups(['loot_pool:write'])]
    public array $chances = [];

    #[Groups(['loot_pool:write'])]
    public array $minAmounts = [];

    #[Groups(['loot_pool:write'])]
    public array $maxAmounts = [];
}