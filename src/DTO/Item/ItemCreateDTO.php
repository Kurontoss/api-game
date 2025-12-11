<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;

class ItemCreateDTO
{
    #[Groups(['item:write'])]
    public ?string $name = null;

    #[Groups(['item:write'])]
    public ?int $value = null;

    #[Groups(['item:write'])]
    public ?string $type = null;

    #[Groups(['item:write'])]
    public ?int $hpRegen = null;
}