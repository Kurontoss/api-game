<?php

namespace App\Tests\Service\Validator\Dungeon;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\Dungeon\ExploreDTO;
use App\Entity\Dungeon;
use App\Entity\Knight;
use App\Repository\DungeonRepository;
use App\Repository\KnightRepository;
use App\Service\Validator\Dungeon\ExploreDTOValidator;
use App\DTO\ErrorDTO;

class ExploreDTOValidatorTest extends TestCase
{
    private ExploreDTOValidator $validator;
    private MockObject $dungeonRepoMock;
    private MockObject $knightRepoMock;

    protected function setUp(): void
    {
        $this->dungeonRepoMock = $this->createMock(DungeonRepository::class);
        $this->knightRepoMock = $this->createMock(KnightRepository::class);

        $this->validator = new ExploreDTOValidator(
            $this->dungeonRepoMock,
            $this->knightRepoMock
        );
    }

    public function testValidateWithValidIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new ExploreDTO();
        $dto->dungeonId = 1;
        $dto->knightId = 2;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Dungeon());

        $this->knightRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(new Knight());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithInvalidDungeonIdReturnsError(): void
    {
        // Given
        $dto = new ExploreDTO();
        $dto->dungeonId = 1;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->knightRepoMock
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

    public function testValidateWithInvalidKnightIdReturnsError(): void
    {
        // Given
        $dto = new ExploreDTO();
        $dto->knightId = 2;

        $this->dungeonRepoMock
            ->expects($this->never())
            ->method('find');

        $this->knightRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('knightId', $errors[0]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[0]->message);
    }

    public function testValidateWithInvalidDungeonAndKnightIdsReturnsBothErrors(): void
    {
        // Given
        $dto = new ExploreDTO();
        $dto->dungeonId = 1;
        $dto->knightId = 2;

        $this->dungeonRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->knightRepoMock
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

        $this->assertEquals('knightId', $errors[1]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[1]->message);
    }

    public function testValidateWithNoIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new ExploreDTO();

        $this->dungeonRepoMock
            ->expects($this->never())
            ->method('find');

        $this->knightRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }
}