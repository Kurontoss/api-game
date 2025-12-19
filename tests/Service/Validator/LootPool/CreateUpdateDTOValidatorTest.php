<?php

namespace App\Tests\Service\Validator\LootPool;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\LootPool\CreateDTO;
use App\DTO\LootPool\UpdateDTO;
use App\DTO\ErrorDTO;
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

    public function testValidateWithValidItemsReturnsNoErrors(): void
    {
        $dto = new CreateDTO();
        $dto->items = [1, 2, 3];

        $this->lootPoolRepoMock
            ->expects($this->exactly(3))
            ->method('find')
            ->willReturnCallback(function ($id) {
                return new LootPool();
            });

        $errors = $this->validator->validate($dto);

        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithSomeInvalidItemsReturnsErrors(): void
    {
        $dto = new CreateDTO();
        $dto->items = [1, 2, 3];

        $this->lootPoolRepoMock
            ->expects($this->exactly(3))
            ->method('find')
            ->willReturnCallback(function ($id) {
                if (in_array($id, [2, 3])) {
                    return null;
                }
                return new LootPool();
            });

        $errors = $this->validator->validate($dto);

        $this->assertCount(2, $errors);
        $this->assertEquals('items[1]', $errors[0]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[0]->message);
        $this->assertEquals('items[2]', $errors[1]->field);
        $this->assertEquals('Entity with index 3 does not exist.', $errors[1]->message);
    }

    public function testValidateWithAllInvalidItemsReturnsErrors(): void
    {
        $dto = new UpdateDTO();
        $dto->items = [10, 20];

        $this->lootPoolRepoMock
            ->expects($this->exactly(2))
            ->method('find')
            ->willReturnCallback(fn($id) => null);

        $errors = $this->validator->validate($dto);

        $this->assertCount(2, $errors);
        $this->assertEquals('items[0]', $errors[0]->field);
        $this->assertEquals('Entity with index 10 does not exist.', $errors[0]->message);
        $this->assertEquals('items[1]', $errors[1]->field);
        $this->assertEquals('Entity with index 20 does not exist.', $errors[1]->message);
    }

    public function testValidateWithEmptyItemsReturnsNoErrors(): void
    {
        $dto = new CreateDTO();
        $dto->items = [];

        $this->lootPoolRepoMock
            ->expects($this->never())
            ->method('find');

        $errors = $this->validator->validate($dto);

        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithNullItemInArraySkipsCheck(): void
    {
        $dto = new CreateDTO();
        $dto->items = [null, 5];

        $this->lootPoolRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(5)
            ->willReturn(new LootPool());

        $errors = $this->validator->validate($dto);

        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }
}