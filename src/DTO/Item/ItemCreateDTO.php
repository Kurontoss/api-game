<?php

namespace App\DTO\Item;

class ItemCreateDTO
{
    public ?string $name = null;

    public ?int $value = null;

    public ?string $type = null;

    public ?int $hpRegen = null;
}