<?php

namespace App\Tests\Service\Validator\LootPool;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\ErrorDTO;
use App\DTO\LootPool\CreateDTO;
use App\DTO\LootPool\UpdateDTO;
use App\Entity\LootPool;
use App\Repository\LootPoolRepository;
use App\Service\Validator\LootPool\CreateUpdateDTOValidator;

class CreateUpdateDTOValidatorTest extends TestCase
{
    private CreateUpdateDTOValidator $validator;
    private MockObject $lootPoolRepoMock;

    protected function setUp(): void
    {
        $this->lootPoolRepoMock = $this->createMock(LootPoolRepository::class);

        $this->validator = new CreateUpdateDTOValidator(
            $this->lootPoolRepoMock
        );
    }

    public function testValidateWithValidItemIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->items = [1, 2];

        $this->lootPoolRepoMock
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturn(new LootPool());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithInvalidItemIdReturnsError(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->items = [1];

        $this->lootPoolRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('items[0]', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);
    }

    public function testValidateWithMixedValidAndInvalidItemIdsReturnsOneError(): void
    {
        // Given
        $dto = new UpdateDTO();
        $dto->items = [1, 2];

        $this->lootPoolRepoMock
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturnCallback(fn ($id) =>
                $id === 1 ? new LootPool() : null
            );

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertEquals('items[1]', $errors[0]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[0]->message);
    }

    public function testValidateWithMultipleInvalidItemIdsReturnsMultipleErrors(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->items = [1, 2];

        $this->lootPoolRepoMock
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(2, $errors);

        $this->assertEquals('items[0]', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);

        $this->assertEquals('items[1]', $errors[1]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[1]->message);
    }

    public function testValidateWithNullItemsIsIgnored(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->items = [null];

        $this->lootPoolRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithEmptyItemsArrayReturnsNoErrors(): void
    {
        // Given
        $dto = new UpdateDTO();
        $dto->items = [];

        $this->lootPoolRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }
}