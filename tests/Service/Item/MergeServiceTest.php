<?php

namespace App\Tests\Service\Item;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\Entity\Item\Item;
use App\Entity\Item\ItemInstance;
use App\Repository\Item\ItemInstanceRepository;
use App\Service\Item\MergeService;

class MergeServiceTest extends TestCase
{
    private MockObject $itemInstanceRepoMock;
    private MergeService $mergeService;

    protected function setUp(): void
    {
        $this->itemInstanceRepoMock = $this->createMock(ItemInstanceRepository::class);
        $this->mergeService = new MergeService($this->itemInstanceRepoMock);
    }

    public function testMergeSameItemsSumsAmountsAndDeletesDuplicates(): void
    {
        // Given
        $item = new Item();

        $itemInstance1 = new ItemInstance();
        $itemInstance1->setItem($item);
        $itemInstance1->setAmount(2);

        $itemInstance2 = new ItemInstance();
        $itemInstance2->setItem($item);
        $itemInstance2->setAmount(3);

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('delete')
            ->with($itemInstance2);

        // When
        $result = $this->mergeService->merge([$itemInstance1, $itemInstance2]);

        // Then
        $this->assertCount(1, $result);
        $this->assertSame($itemInstance1, $result[0]);
        $this->assertEquals(5, $result[0]->getAmount());
    }

    public function testMergeDifferentItemsDoesNotMerge(): void
    {
        // Given
        $item1 = new Item();
        $item2 = new Item();

        $instance1 = new ItemInstance();
        $instance1->setItem($item1);
        $instance1->setAmount(1);

        $instance2 = new ItemInstance();
        $instance2->setItem($item2);
        $instance2->setAmount(1);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('delete');

        // When
        $result = $this->mergeService->merge([$instance1, $instance2]);

        // Then
        $this->assertCount(2, $result);
    }

    public function testMergeWithCloneDoesNotDeleteAndReturnsClones(): void
    {
        // Given
        $item = new Item();

        $itemInstance1 = new ItemInstance();
        $itemInstance1->setItem($item);
        $itemInstance1->setAmount(2);

        $itemInstance2 = new ItemInstance();
        $itemInstance2->setItem($item);
        $itemInstance2->setAmount(3);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('delete');

        // When
        $result = $this->mergeService->merge([$itemInstance1, $itemInstance2], true);

        // Then
        $this->assertCount(1, $result);
        $this->assertEquals(5, $result[0]->getAmount());
        $this->assertNotSame($itemInstance1, $result[0]);
        $this->assertNotSame($itemInstance2, $result[0]);
    }

    public function testMergeSingleItemReturnsSameItem(): void
    {
        // Given
        $item = new Item();

        $itemInstance = new ItemInstance();
        $itemInstance->setItem($item);
        $itemInstance->setAmount(2);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('delete');

        // When
        $result = $this->mergeService->merge([$itemInstance]);

        // Then
        $this->assertCount(1, $result);
        $this->assertSame($itemInstance, $result[0]);
    }

    public function testMergeMultipleDuplicatesDeletesAllButFirst(): void
    {
        // Given
        $item = new Item();

        $first = new ItemInstance();
        $first->setItem($item);
        $first->setAmount(1);

        $second = new ItemInstance();
        $second->setItem($item);
        $second->setAmount(2);

        $third = new ItemInstance();
        $third->setItem($item);
        $third->setAmount(3);

        $deletedItems = [];

        $this->itemInstanceRepoMock
            ->expects($this->exactly(2))
            ->method('delete')
            ->willReturnCallback(function (ItemInstance $item) use (&$deletedItems) {
                $deletedItems[] = $item;
            });

        // When
        $result = $this->mergeService->merge([$first, $second, $third]);

        // Then
        $this->assertCount(1, $result);
        $this->assertSame($first, $result[0]);
        $this->assertEquals(6, $result[0]->getAmount());

        $this->assertContains($second, $deletedItems);
        $this->assertContains($third, $deletedItems);
    }
}