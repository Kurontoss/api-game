<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ArraysEqualLength extends Constraint
{
    public string $message = 'Arrays {{ arrays }} must be equal length.';

    public array $arrayFields;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!isset($this->arrayFields) || count($arrayFields) < 2) {
            throw new \InvalidArgumentException('You must provide at least 2 fields.');
        }
    }

    public function getRequiredOptions(): array
    {
        return ['arrayFields'];
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}