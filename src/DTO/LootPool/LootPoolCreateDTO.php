<?php

namespace App\DTO\LootPool;

class LootPoolCreateDTO
{
    public ?string $name = null;

    public array $items = [];
    
    public array $chances = [];

    public array $minAmounts = [];

    public array $maxAmounts = [];
}