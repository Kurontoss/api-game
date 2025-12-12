<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    public function validate(mixed $object): array
    {
        $violations = $this->validator->validate($object);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = new ErrorDTO(
                field: $violation->getPropertyPath(),
                message: $violation->getMessage()
            );
        }

        return $errors;
    }
}