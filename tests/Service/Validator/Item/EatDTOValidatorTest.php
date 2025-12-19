<?php

namespace App\Tests\Service\Validator\Item;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\Item\EatDTO;
use App\DTO\ErrorDTO;
use App\Entity\Item\ItemInstance;
use App\Entity\Knight;
use App\Repository\KnightRepository;
use App\Repository\Item\ItemInstanceRepository;
use App\Service\Validator\Item\EatDTOValidator;

class EatDTOValidatorTest extends TestCase
{
    private EatDTOValidator $validator;
    private MockObject $knightRepoMock;
    private MockObject $itemInstanceRepoMock;

    protected function setUp(): void
    {
        $this->knightRepoMock = $this->createMock(KnightRepository::class);
        $this->itemInstanceRepoMock = $this->createMock(ItemInstanceRepository::class);

        $this->validator = new EatDTOValidator(
            $this->knightRepoMock,
            $this->itemInstanceRepoMock
        );
    }

    public function testValidateWithValidIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new EatDTO();
        $dto->knightId = 1;
        $dto->itemInstanceId = 2;

        $this->knightRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Knight());

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(new ItemInstance());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateWithInvalidKnightIdReturnsError(): void
    {
        // Given
        $dto = new EatDTO();
        $dto->knightId = 1;

        $this->knightRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('knightId', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);
    }

    public function testValidateWithInvalidItemInstanceIdReturnsError(): void
    {
        // Given
        $dto = new EatDTO();
        $dto->itemInstanceId = 2;

        $this->knightRepoMock
            ->expects($this->never())
            ->method('find');

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('itemInstanceId', $errors[0]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[0]->message);
    }

    public function testValidateWithInvalidKnightAndItemInstanceIdsReturnsBothErrors(): void
    {
        // Given
        $dto = new EatDTO();
        $dto->knightId = 1;
        $dto->itemInstanceId = 2;

        $this->knightRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->itemInstanceRepoMock
            ->expects($this->once())
            ->method('find')
            ->with(2)
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(2, $errors);

        $this->assertEquals('knightId', $errors[0]->field);
        $this->assertEquals('Entity with index 1 does not exist.', $errors[0]->message);

        $this->assertEquals('itemInstanceId', $errors[1]->field);
        $this->assertEquals('Entity with index 2 does not exist.', $errors[1]->message);
    }

    public function testValidateWithNoIdsReturnsNoErrors(): void
    {
        // Given
        $dto = new EatDTO();

        $this->knightRepoMock
            ->expects($this->never())
            ->method('find');

        $this->itemInstanceRepoMock
            ->expects($this->never())
            ->method('find');

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }
}