<?php

namespace App\Tests\Service\Knight;

use App\Entity\Knight;
use App\Service\Knight\LevelUpService;
use PHPUnit\Framework\TestCase;

class LevelUpServiceTest extends TestCase
{
    private LevelUpService $levelUpService;

    protected function setUp(): void
    {
        $this->levelUpService = new LevelUpService();
    }

    public function testNoLevelUpWhenExpIsInsufficient(): void
    {
        // Given
        $knight = new Knight();
        $knight->setLevel(1);
        $knight->setExp(9);
        $knight->setExpToNextLevel(10);
        $knight->setMaxHp(100);

        // When
        $this->levelUpService->levelUp($knight);

        // Then
        $this->assertEquals(1, $knight->getLevel());
        $this->assertEquals(9, $knight->getExp());
        $this->assertEquals(10, $knight->getExpToNextLevel());
        $this->assertEquals(100, $knight->getMaxHp());
    }

    public function testExactExpLevelsUpOnce(): void
    {
        // Given
        $knight = new Knight();
        $knight->setLevel(1);
        $knight->setExp(10);
        $knight->setExpToNextLevel(10);
        $knight->setMaxHp(100);

        // When
        $this->levelUpService->levelUp($knight);

        // Then
        $this->assertEquals(2, $knight->getLevel());
        $this->assertEquals(0, $knight->getExp());
        $this->assertEquals(15, $knight->getExpToNextLevel());
        $this->assertEquals(120, $knight->getMaxHp());
    }

    public function testMultipleLevelUpsWithExpOverflow(): void
    {
        // Given
        $knight = new Knight();
        $knight->setLevel(1);
        $knight->setExp(40);
        $knight->setExpToNextLevel(10);
        $knight->setMaxHp(100);

        // When
        $this->levelUpService->levelUp($knight);

        // Then
        $this->assertEquals(3, $knight->getLevel());

        // 40 - 10 - 15 = 15
        $this->assertEquals(15, $knight->getExp());

        // 10 * 1.5 * 1.5 ~= 22
        $this->assertEquals(22, $knight->getExpToNextLevel());

        // 100 * 1.2 * 1.2 = 144
        $this->assertEquals(144, $knight->getMaxHp());
    }

    public function testExpStopsJustBelowNextThreshold(): void
    {
        // Given
        $knight = new Knight();
        $knight->setLevel(1);
        $knight->setExp(24);
        $knight->setExpToNextLevel(10);
        $knight->setMaxHp(100);

        // When
        $this->levelUpService->levelUp($knight);

        // Then
        $this->assertEquals(2, $knight->getLevel());
        $this->assertEquals(14, $knight->getExp());
        $this->assertEquals(15, $knight->getExpToNextLevel());
        $this->assertEquals(120, $knight->getMaxHp());
    }
}