<?php

namespace App\Tests\Service\Validator\User;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use App\DTO\ErrorDTO;
use App\DTO\User\CreateDTO;
use App\DTO\User\UpdateDTO;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Validator\User\CreateUpdateDTOValidator;

class CreateUpdateDTOValidatorTest extends TestCase
{
    private CreateUpdateDTOValidator $validator;
    private MockObject $userRepoMock;

    protected function setUp(): void
    {
        $this->userRepoMock = $this->createMock(UserRepository::class);

        $this->validator = new CreateUpdateDTOValidator(
            $this->userRepoMock
        );
    }

    public function testValidateCreateDtoWithNewEmailReturnsNoErrors(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->email = 'test@example.com';

        $this->userRepoMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'test@example.com'])
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateCreateDtoWithExistingEmailReturnsError(): void
    {
        // Given
        $dto = new CreateDTO();
        $dto->email = 'test@example.com';

        $this->userRepoMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'test@example.com'])
            ->willReturn(new User());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(ErrorDTO::class, $errors[0]);
        $this->assertEquals('email', $errors[0]->field);
        $this->assertEquals(
            'Email test@example.com already registered.',
            $errors[0]->message
        );
    }

    public function testValidateUpdateDtoWithNewEmailReturnsNoErrors(): void
    {
        // Given
        $dto = new UpdateDTO();
        $dto->email = 'new@example.com';

        $this->userRepoMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'new@example.com'])
            ->willReturn(null);

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertIsArray($errors);
        $this->assertCount(0, $errors);
    }

    public function testValidateUpdateDtoWithExistingEmailReturnsError(): void
    {
        // Given
        $dto = new UpdateDTO();
        $dto->email = 'existing@example.com';

        $this->userRepoMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => 'existing@example.com'])
            ->willReturn(new User());

        // When
        $errors = $this->validator->validate($dto);

        // Then
        $this->assertCount(1, $errors);
        $this->assertEquals('email', $errors[0]->field);
        $this->assertEquals(
            'Email existing@example.com already registered.',
            $errors[0]->message
        );
    }
}