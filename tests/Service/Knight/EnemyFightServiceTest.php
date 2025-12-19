<?php

namespace App\Tests\Service\Knight;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\Battle\FightDTO;
use App\Entity\Enemy;
use App\Entity\LootPool;
use App\Entity\Knight;
use App\Entity\Item\ItemInstance;
use App\Repository\Item\ItemInstanceRepository;
use App\Repository\KnightRepository;
use App\Service\Knight\EnemyFightService;
use App\Service\LootService;

class EnemyFightServiceTest extends TestCase
{
    private EnemyFightService $fightService;
    private MockObject $lootServiceMock;
    private MockObject $itemInstanceRepoMock;
    private MockObject $knightRepoMock;

    protected function setUp(): void
    {
        $this->lootServiceMock = $this->createMock(LootService::class);
        $this->itemInstanceRepoMock = $this->createMock(ItemInstanceRepository::class);
        $this->knightRepoMock = $this->createMock(KnightRepository::class);

        $this->fightService = new EnemyFightService(
            $this->lootServiceMock,
            $this->itemInstanceRepoMock,
            $this->knightRepoMock
        );
    }

    public function testFightKnightLoses(): void
    {
        // Given
        $knight = new Knight();
        $knight->setHp(10);
        $knight->setLevel(1);

        $enemy = new Enemy();
        $enemy->setHp(50);
        $enemy->setStrength(5);

        // Expect
        $this->lootServiceMock->expects($this->never())->method('drop');
        $this->itemInstanceRepoMock->expects($this->never())->method('save');
        $this->knightRepoMock->expects($this->never())->method('save');

        // When
        $fight = $this->fightService->fight($knight, $enemy);

        // Then
        $this->assertInstanceOf(FightDTO::class, $fight);
        $this->assertFalse($fight->isWon);
        $this->assertEquals(1, $knight->getHp());
        $this->assertEquals(1, $fight->knight->getHp());
    }

    public function testFightKnightWinsAndGetsLoot(): void
    {
        // Given
        $knight = new Knight();
        $knight->setHp(100);
        $knight->setLevel(10);
        $knight->setExp(0);

        $enemy = new Enemy();
        $enemy->setHp(10);
        $enemy->setStrength(1);
        $enemy->setExp(20);
        $enemy->setLootPool(new LootPool());

        $item = new ItemInstance();

        $this->lootServiceMock
            ->expects($this->once())
            ->method('drop')
            ->with($enemy->getLootPool())
            ->willReturn($item);

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('save')
            ->with($item);

        $this->knightRepoMock
            ->expects($this->once())
            ->method('save')
            ->with($knight);

        // When
        $fight = $this->fightService->fight($knight, $enemy);

        // Then
        $this->assertTrue($fight->isWon);
        $this->assertSame($item, $fight->item);
        $this->assertSame($knight, $item->getKnight());
    }


}