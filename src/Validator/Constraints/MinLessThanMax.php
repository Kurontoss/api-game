<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MinLessThanMax extends Constraint
{
    public string $message = 'The minimum value "{{ min }}" must be less than the maximum value "{{ max }}" at index {{ index }}.';

    public string $minField;
    public string $maxField;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (!isset($this->minField) || !isset($this->maxField)) {
            throw new \InvalidArgumentException('You must provide "minField" and "maxField" options.');
        }
    }

    public function getRequiredOptions(): array
    {
        return ['minField', 'maxField'];
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}