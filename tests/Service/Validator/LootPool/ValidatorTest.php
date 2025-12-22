<?php

namespace App\Tests\Service\Validator\LootPool;

use PHPUnit\Framework\TestCase;

use App\DTO\ErrorDTO;
use App\Entity\LootPool;
use App\Entity\Item\Item;
use App\Service\Validator\LootPool\Validator;
use Doctrine\Common\Collections\ArrayCollection;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        $this->validator = new Validator();
    }

    private function createValidLootPool(): LootPool
    {
        $lootPool = new LootPool();

        $items = new ArrayCollection([
            new Item(),
            new Item(),
        ]);

        $lootPool
            ->setItems($items)
            ->setChances([0.4, 0.6])
            ->setMinAmounts([1, 2])
            ->setMaxAmounts([3, 4]);

        return $lootPool;
    }

    public function testValidateWithValidLootPoolReturnsNoErrors(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithItemsAndChancesLengthMismatchReturnsError(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();
        $lootPool->setChances([1.0]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(2, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('items', $errors[0]->field);
        $this->assertEquals(
            'arrays items and chances are not of equal length',
            $errors[0]->message
        );
    }

    public function testValidateWithChancesAndMinAmountsLengthMismatchReturnsError(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();
        $lootPool->setMinAmounts([1]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(2, $errors);
        $this->assertEquals('chances', $errors[0]->field);
        $this->assertEquals(
            'arrays chances and minAmounts are not of equal length',
            $errors[0]->message
        );
    }

    public function testValidateWithMinAndMaxAmountsLengthMismatchReturnsError(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();
        $lootPool->setMaxAmounts([5]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(1, $errors);
        $this->assertEquals('minAmounts', $errors[0]->field);
        $this->assertEquals(
            'arrays minAmounts and maxAmounts are not of equal length',
            $errors[0]->message
        );
    }

    public function testValidateWithChancesNotAddingUpToOneReturnsError(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();
        $lootPool->setChances([0.2, 0.2]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(1, $errors);
        $this->assertEquals('chances', $errors[0]->field);
        $this->assertEquals(
            'Elements of array add up to 0.4 instead of 1',
            $errors[0]->message
        );
    }

    public function testValidateWithMinAmountGreaterThanMaxAmountReturnsError(): void
    {
        // Given
        $lootPool = $this->createValidLootPool();
        $lootPool->setMinAmounts([5, 2]);
        $lootPool->setMaxAmounts([3, 4]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(1, $errors);
        $this->assertEquals('minAmounts[0]', $errors[0]->field);
        $this->assertEquals(
            'The minimum value 5 must be less than the maximum value 3at index 0.',
            $errors[0]->message
        );
    }

    public function testValidateWithMultipleErrorsReturnsAllErrors(): void
    {
        // Given
        $lootPool = new LootPool();

        $lootPool
            ->setItems(new ArrayCollection([new Item()]))
            ->setChances([0.5, 0.5])
            ->setMinAmounts([5])
            ->setMaxAmounts([2]);

        // When
        $errors = $this->validator->validate($lootPool);

        // Then
        $this->assertCount(3, $errors);

        $this->assertEquals('items', $errors[0]->field);
        $this->assertEquals('chances', $errors[1]->field);
        $this->assertEquals('minAmounts[0]', $errors[2]->field);
    }
}