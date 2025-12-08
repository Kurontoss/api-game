<?php

namespace App\DTO\Enemy;

class EnemyCreateDTO
{
    public ?string $name = null;

    public ?int $hp = null;

    public ?int $strength = null;

    public ?int $exp = null;

    public ?int $dungeonId = null;
}