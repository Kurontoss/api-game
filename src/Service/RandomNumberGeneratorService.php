<?php

namespace App\Service;

class RandomNumberGeneratorService
{
    public function generateFloat(): float
    {
        return (float)random_int(1, 100000) / 100000;
    }

    public function generateIntFromRange(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}