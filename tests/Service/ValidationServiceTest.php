<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\DTO\ResponseErrorDTO;
use App\Entity\Enemy;
use App\Service\ValidationService;

class ValidationServiceTest extends TestCase
{
    protected ValidationService $validationService;
    protected ValidatorInterface $validatorStub;

    protected function setUp(): void
    {
        $this->validatorStub = $this->createStub(ValidatorInterface::class);
        $this->validationService = new ValidationService($this->validatorStub);
    }

    public function testValidateMapsSingleConstraintViolationToResponseErrorDTO(): void
    {
        // Given
        $violations = new ConstraintViolationList([
            new ConstraintViolation(
                message: 'This value should not be blank.',
                messageTemplate: null,
                parameters: [],
                root: null,
                propertyPath: 'name',
                invalidValue: null
            ),
        ]);

        $this->validatorStub
            ->method('validate')
            ->willReturn($violations);
                
        // When
        $result = $this->validationService->validate(new Enemy());

        // Then
        $this->assertInstanceOf(ResponseErrorDTO::class, $result);
        $this->assertSame('Validation error', $result->reason);
        $this->assertSame('Validation error', $result->message);
        $this->assertNotEmpty($result->errors);
        $this->assertCount(1, $result->errors);
        $this->assertSame('name', $result->errors[0]->field);
        $this->assertSame('This value should not be blank.', $result->errors[0]->message);
    }

    public function testValidateMapsConstraintViolationsToResponseErrorDTO(): void
    {
        // Given
        $violations = new ConstraintViolationList([
            new ConstraintViolation(
                message: 'This value should not be blank.',
                messageTemplate: null,
                parameters: [],
                root: null,
                propertyPath: 'name',
                invalidValue: null
            ),
            new ConstraintViolation(
                message: 'This value should be positive.',
                messageTemplate: null,
                parameters: [],
                root: null,
                propertyPath: 'hp',
                invalidValue: null
            ),
        ]);

        $this->validatorStub
            ->method('validate')
            ->willReturn($violations);

        // When
        $result = $this->validationService->validate(new Enemy());

        // Then
        $this->assertInstanceOf(ResponseErrorDTO::class, $result);
        $this->assertSame('Validation error', $result->reason);
        $this->assertSame('Validation error', $result->message);
        $this->assertNotEmpty($result->errors);
        $this->assertCount(2, $result->errors);
        $this->assertSame('name', $result->errors[0]->field);
        $this->assertSame('This value should not be blank.', $result->errors[0]->message);
        $this->assertSame('hp', $result->errors[1]->field);
        $this->assertSame('This value should be positive.', $result->errors[1]->message);
    }

    public function testValidateMapsNoConstraintViolationsToEmptyResponseErrorDTO(): void
    {
        // Given
        $violations = new ConstraintViolationList();

        $this->validatorStub
            ->method('validate')
            ->willReturn($violations);
        
        // When
        $result = $this->validationService->validate(new Enemy());

        // Then
        $this->assertInstanceOf(ResponseErrorDTO::class, $result);
        $this->assertSame('Validation error', $result->reason);
        $this->assertSame('Validation error', $result->message);
        $this->assertEmpty($result->errors);
    }
}