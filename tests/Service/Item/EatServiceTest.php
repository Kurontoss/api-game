<?php

namespace App\Tests\Service\Item;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\Entity\Item\Food;
use App\Entity\Item\ItemInstance;
use App\Entity\Knight;
use App\Exception\ItemAmountTooLowException;
use App\Repository\Item\ItemInstanceRepository;
use App\Service\Item\EatService;

class EatServiceTest extends TestCase
{
    private MockObject $itemInstanceRepoMock;
    private EatService $eatService;

    protected function setUp(): void
    {
        $this->itemInstanceRepoMock = $this->createMock(ItemInstanceRepository::class);
        $this->eatService = new EatService($this->itemInstanceRepoMock);
    }

    public function testEatReducesItemAmountAndHealsKnight(): void
    {
        // Given
        $knight = new Knight();
        $knight->setHp(50);
        $knight->setMaxHp(100);

        $item = new Food();
        $item->setHpRegen(10);

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($item);
        $itemInstance->setAmount(3);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('delete');

        // When
        $this->eatService->eat($knight, $itemInstance, 2);

        // Then
        $this->assertEquals(50 + (10 * 2), $knight->getHp());
        $this->assertEquals(1, $itemInstance->getAmount());
    }

    public function testEatExactRemainingAmountDeletesItem(): void
    {
        // Given
        $knight = new Knight();
        $knight->setHp(40);
        $knight->setMaxHp(100);

        $item = new Food();
        $item->setHpRegen(10);

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($item);
        $itemInstance->setAmount(3);

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('delete')
            ->with($itemInstance);

        // When
        $this->eatService->eat($knight, $itemInstance, 3);

        // Then
        $this->assertEquals(40 + (10 * 3), $knight->getHp());
        $this->assertEquals(0, $itemInstance->getAmount());
    }

    public function testEatDoesNotHealAboveMaxHp(): void
    {
        // Given
        $knight = new Knight();
        $knight->setHp(95);
        $knight->setMaxHp(100);

        $item = new Food();
        $item->setHpRegen(10);

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($item);
        $itemInstance->setAmount(2);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('delete');

        // When
        $this->eatService->eat($knight, $itemInstance, 1);

        // Then
        $this->assertEquals(100, $knight->getHp());
        $this->assertEquals(1, $itemInstance->getAmount());
    }

    public function testEatMoreThanAvailableThrowsException(): void
{
    // Given
    $knight = new Knight();

    $item = new Food();
    $item->setHpRegen(10);

    $itemInstance = new ItemInstance();
    $itemInstance->setItem($item);
    $itemInstance->setAmount(1);

    $this->itemInstanceRepoMock
        ->expects($this->never())
        ->method('delete');

    // Then
    $this->expectException(ItemAmountTooLowException::class);

    // When
    $this->eatService->eat($knight, $itemInstance, 2);
}
}