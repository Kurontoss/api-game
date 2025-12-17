<?php

namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\DTO\ErrorDTO;
use App\DTO\ResponseErrorDTO;

class ValidationService
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {}

    public function validate(mixed $object): ResponseErrorDTO
    {
        $violations = $this->validator->validate($object);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[] = new ErrorDTO(
                field: $violation->getPropertyPath(),
                message: $violation->getMessage()
            );
        }

        $response = new ResponseErrorDTO();
        $response->reason = 'Validation error';
        $response->message = 'Validation error';
        $response->errors = $errors;

        return $response;
    }
}