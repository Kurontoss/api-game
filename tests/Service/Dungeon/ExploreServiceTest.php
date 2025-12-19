<?php

namespace App\Tests\Service\Dungeon;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use App\DTO\Battle\BattleSummaryDTO;
use App\DTO\Battle\FightDTO;
use App\Entity\Dungeon;
use App\Entity\Enemy;
use App\Entity\Item\ItemInstance;
use App\Entity\Knight;
use App\Entity\User;
use App\Exception\LevelTooLowException;
use App\Service\Knight\EnemyFightService;
use App\Service\Dungeon\ExploreService;
use App\Service\Knight\LevelUpService;
use App\Service\Item\MergeService;

class ExploreServiceTest extends TestCase
{
    protected EnemyFightService $enemyFightServiceStub;
    protected MergeService $mergeServiceStub;
    protected LevelUpService $levelUpServiceStub;
    protected ExploreService $exploreService;

    protected Knight $knight;
    protected array $items;
    protected array $enemies;

    protected function setUp(): void
    {
        $this->enemyFightServiceStub = $this->createStub(EnemyFightService::class);

        $this->knight = new Knight();
        $this->knight->setExp(0);
        $this->knight->setLevel(1);
        $this->knight->setInventory(new ArrayCollection());

        $this->items = [new ItemInstance(), new ItemInstance(), new ItemInstance()];
        $this->enemies = [new Enemy(), new Enemy(), new Enemy()];

        $this->enemyFightServiceStub
            ->method('fight')
            ->willReturnOnConsecutiveCalls(
                new FightDTO(
                    index: 0,
                    enemy: $this->enemies[0],
                    knight: $this->knight,
                    exp: 1,
                    item: $this->items[0],
                    isWon: true,
                ),
                new FightDTO(
                    index: 0,
                    enemy: $this->enemies[1],
                    knight: $this->knight,
                    exp: 1,
                    item: $this->items[1],
                    isWon: true,
                ),
                new FightDTO(
                    index: 0,
                    enemy: $this->enemies[2],
                    knight: $this->knight,
                    exp: 1,
                    item: $this->items[2],
                    isWon: false,
                ),
            );

        $this->mergeServiceStub = $this->createStub(MergeService::class);
        $this->mergeServiceStub
            ->method('merge')
            ->willReturnCallback(fn(array $items, bool $clone = false) => $items);

        $this->levelUpServiceStub = $this->createStub(LevelUpService::class);

        $this->exploreService = new ExploreService(
            $this->enemyFightServiceStub,
            $this->mergeServiceStub,
            $this->levelUpServiceStub,
        );
    }

    public function testExploreReturnCorrectBattleSummaryWhenOneEnemy(): void
    {
        // Given
        $dungeon = new Dungeon();
        $dungeon->setName('dungeon');
        $dungeon->setLevel(1);
        $dungeon->setExp(1);
        $dungeon->addEnemy($this->enemies[0]);

        // When
        $result = $this->exploreService->explore($this->knight, $dungeon);

        // Then
        $this->assertInstanceOf(BattleSummaryDTO::class, $result);
        $this->assertSame(2, $result->exp);

        $this->assertNotEmpty($result->items);
        $this->assertCount(1, $result->items);
        $this->assertSame($this->items[0], $result->items[0]);

        $this->assertNotEmpty($result->fights);
        $this->assertCount(2, $result->fights);

        $fight = $result->fights[1];
        $this->assertInstanceOf(FightDTO::class, $fight);
        $this->assertSame(1, $fight->index);
        $this->assertSame($this->enemies[0], $fight->enemy);
        $this->assertSame($this->knight, $fight->knight);
        $this->assertSame(1, $fight->exp);
        $this->assertSame($this->items[0], $fight->item);
        $this->assertTrue($fight->isWon);
    }

    public function testExploreReturnCorrectBattleSummaryWhenMultipleEnemies(): void
    {
        // Given
        $dungeon = new Dungeon();
        $dungeon->setName('dungeon');
        $dungeon->setLevel(1);
        $dungeon->setExp(1);
        $dungeon->addEnemy($this->enemies[0]);
        $dungeon->addEnemy($this->enemies[1]);

        // When
        $result = $this->exploreService->explore($this->knight, $dungeon);

        // Then
        $this->assertInstanceOf(BattleSummaryDTO::class, $result);
        $this->assertSame(3, $result->exp);

        $this->assertNotEmpty($result->items);
        $this->assertCount(2, $result->items);

        $this->assertNotEmpty($result->fights);
        $this->assertCount(3, $result->fights);

        for ($i = 0; $i < 2; $i++) {
            $fight = $result->fights[$i + 1];
            $this->assertInstanceOf(FightDTO::class, $fight);
            $this->assertSame($i + 1, $fight->index);
            $this->assertSame($this->enemies[$i], $fight->enemy);
            $this->assertSame($this->knight, $fight->knight);
            $this->assertSame(1, $fight->exp);
            $this->assertSame($this->items[$i], $fight->item);
            $this->assertTrue($fight->isWon);
        }
    }

    public function testExploreThrowsLevelTooLowException(): void
    {
        // Given
        $knight = new Knight();
        $knight->setLevel(1);

        $dungeon = new Dungeon();
        $dungeon->setLevel(5);

        // Then
        $this->expectException(LevelTooLowException::class);

        // When
        $this->exploreService->explore($knight, $dungeon);
    }

    public function testExploreStopsWhenFightIsLost(): void
    {
        // Given
        $dungeon = new Dungeon();
        $dungeon->setName('dungeon');
        $dungeon->setLevel(1);
        $dungeon->setExp(1);
        $dungeon->addEnemy($this->enemies[0]);
        $dungeon->addEnemy($this->enemies[1]);
        $dungeon->addEnemy($this->enemies[2]);

        // When
        $result = $this->exploreService->explore($this->knight, $dungeon);

        // Then
        $this->assertInstanceOf(BattleSummaryDTO::class, $result);
        $this->assertSame(2, $result->exp);

        $this->assertNotEmpty($result->items);
        $this->assertCount(2, $result->items);

        $this->assertNotEmpty($result->fights);
        $this->assertCount(4, $result->fights);

        for ($i = 0; $i < 3; $i++) {
            $fight = $result->fights[$i + 1];
            $this->assertInstanceOf(FightDTO::class, $fight);
            $this->assertSame($i + 1, $fight->index);
            $this->assertSame($this->enemies[$i], $fight->enemy);
            $this->assertSame($this->knight, $fight->knight);
            $this->assertSame(1, $fight->exp);
            $this->assertSame($this->items[$i], $fight->item);
        }

        $this->assertTrue($result->fights[1]->isWon);
        $this->assertTrue($result->fights[2]->isWon);
        $this->assertFalse($result->fights[3]->isWon);
    }
}