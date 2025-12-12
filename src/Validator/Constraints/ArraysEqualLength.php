<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class ArraysEqualLength extends Constraint
{
    public string $message = 'Arrays {{ arr1 }} and {{ arr2 }} must be equal length.';

    public array $arrayFields;

    public function __construct(
        array $arrayFields,
        string $message = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(groups: $groups, payload: $payload);

        $this->arrayFields = $arrayFields;

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