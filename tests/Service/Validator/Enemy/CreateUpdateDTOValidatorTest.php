<?php

namespace App\Tests\Service\Validator\Enemy;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\Enemy\CreateDTO;
use App\DTO\Enemy\UpdateDTO;
use App\DTO\ErrorDTO;
use App\Entity\Dungeon;
use App\Entity\LootPool;
use App\Repository\DungeonRepository;
use App\Repository\LootPoolRepository;
use App\Service\Validator\Enemy\CreateUpdateDTOValidator;

class CreateUpdateDTOValidatorTest extends TestCase
{
    private CreateUpdateDTOValidator $validator;
    private MockObject $dungeonRepoMock;
    private MockObject $lootPoolRepoMock;

    protected function setUp(): void
    {
        $this->dungeonRepoMock = $this->createMock(DungeonRepository::class);
        $this->lootPoolRepoMock = $this->createMock(LootPoolRepository::class);

        $this->validator = new CreateUpdateDTOValidator(
            $this->dungeonRepoMock,
            $this->lootPoolRepoMock
        );
    }

    public function testValidateWithValidIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->dungeonId = 1;
        $dto->lootPoolId = 2;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Dungeon());

        $this->lootPoolRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(new LootPool());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithInvalidDungeonIdReturnsError(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->dungeonId = 1;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->lootPoolRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('dungeonId', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);
    }

    public function testValidateWithInvalidLootPoolIdReturnsError(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->lootPoolId = 2;

        $this->dungeonRepoMock
            ->expects($this->never())
            ->method('find');

        $this->lootPoolRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('lootPoolId', $errors[0]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[0]->message);
    }

    public function testValidateWithInvalidDungeonAndLootPoolIdsReturnsBothErrors(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->dungeonId = 1;
        $dto->lootPoolId = 2;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->lootPoolRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(2, $errors);

        $this->assertEquals('dungeonId', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);

        $this->assertEquals('lootPoolId', $errors[1]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[1]->message);
    }

    public function testValidateWithNoIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new CreateDTO();

        $this->dungeonRepoMock
            ->expects($this->never())
            ->method('find');

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