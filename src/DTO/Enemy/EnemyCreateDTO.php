<?php

namespace App\DTO\Enemy;

use Symfony\Component\Serializer\Annotation\Groups;

class EnemyCreateDTO
{
    #[Groups(['enemy:write'])]
    public ?string $name = null;

    #[Groups(['enemy:write'])]
    public ?int $hp = null;

    #[Groups(['enemy:write'])]
    public ?int $strength = null;

    #[Groups(['enemy:write'])]
    public ?int $exp = null;

    #[Groups(['enemy:write'])]
    public ?int $dungeonId = null;

    #[Groups(['enemy:write'])]
    public ?int $lootPoolId = null;
}