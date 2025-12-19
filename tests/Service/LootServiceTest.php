<?php

namespace App\Tests\Service;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

use App\Entity\Item\Item;
use App\Entity\Item\ItemInstance;
use App\Entity\LootPool;
use App\Repository\Item\ItemInstanceRepository;
use App\Service\LootService;
use App\Service\RandomNumberGeneratorService;

class LootServiceTest extends TestCase
{
    protected $randomNumberGeneratorStub;
    protected $lootService;

    protected function setUp(): void
    {
        $this->randomNumberGeneratorStub = $this->createStub(RandomNumberGeneratorService::class);
        $this->lootService = new LootService($this->randomNumberGeneratorStub);
    }

    public function testDropReturnsItemInstanceWhenChanceHits(): void
    {
        // Given
        $this->randomNumberGeneratorStub
            ->method('generateFloat')
            ->willReturn(0.5);
        
        $this->randomNumberGeneratorStub
            ->method('generateIntFromRange')
            ->willReturn(1);

        $item = new Item();

        $lootPool = $this->createConfiguredStub(LootPool::class, [
            'getItems' => new ArrayCollection([$item]),
            'getChances' => [1.0],
            'getMinAmounts' => [1],
            'getMaxAmounts' => [1],
        ]);

        // When
        $result = $this->lootService->drop($lootPool);

        // Then
        $this->assertInstanceOf(ItemInstance::class, $result);
        $this->assertSame($item, $result->getItem());
        $this->assertEquals(1, $result->getAmount());
    }

    public function testDropReturnsNullWhenItemIsNull(): void
    {
        // Given
        $this->randomNumberGeneratorStub
            ->method('generateFloat')
            ->willReturn(0.5);

        $this->randomNumberGeneratorStub
            ->method('generateIntFromRange')
            ->willReturn(1);

        $item = new Item();

        $lootPool = $this->createConfiguredStub(LootPool::class, [
            'getItems' => new ArrayCollection([null]),
            'getChances' => [1.0],
            'getMinAmounts' => [1],
            'getMaxAmounts' => [1],
        ]);

        // When
        $result = $this->lootService->drop($lootPool);

        // Then
        $this->assertNull($result);
    }

    public function testDropReturnsCorrectItemFromMultipleItems(): void
    {
        // Given
        $this->randomNumberGeneratorStub
            ->method('generateFloat')
            ->willReturn(0.67);

        $this->randomNumberGeneratorStub
            ->method('generateIntFromRange')
            ->willReturn(1);

        $item1 = new Item();
        $item2 = new Item();

        $lootPool = $this->createConfiguredStub(LootPool::class, [
            'getItems' => new ArrayCollection([$item1, $item2]),
            'getChances' => [0.6, 0.4],
            'getMinAmounts' => [1, 1],
            'getMaxAmounts' => [1, 1],
        ]);

        // When
        $result = $this->lootService->drop($lootPool);

        // Then
        $this->assertInstanceOf(ItemInstance::class, $result);
        $this->assertSame($item2, $result->getItem());
        $this->assertEquals(1, $result->getAmount());
    }

    public function testDropReturnsCorrectAmountOfItems(): void
    {
        // Given
        $this->randomNumberGeneratorStub
            ->method('generateFloat')
            ->willReturn(0.5);

        $this->randomNumberGeneratorStub
            ->method('generateIntFromRange')
            ->willReturn(3);
        
        $item = new Item();

        $lootPool = $this->createConfiguredStub(LootPool::class, [
            'getItems' => new ArrayCollection([$item]),
            'getChances' => [1.0],
            'getMinAmounts' => [1],
            'getMaxAmounts' => [10],
        ]);

        // When
        $result = $this->lootService->drop($lootPool);

        // Then
        $this->assertInstanceOf(ItemInstance::class, $result);
        $this->assertSame($item, $result->getItem());
        $this->assertEquals(3, $result->getAmount());
    }
}