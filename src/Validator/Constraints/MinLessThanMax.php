<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class MinLessThanMax extends Constraint
{
    public string $message = 'The minimum value "{{ min }}" must be less than the maximum value "{{ max }}" at index {{ index }}.';

    public string $minField;
    public string $maxField;

    public function __construct(
        string $minField,
        string $maxField,
        string $message = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(groups: $groups, payload: $payload);

        $this->minField = $minField;
        $this->maxField = $maxField;

        if (!isset($this->minField) || !isset($this->maxField)) {
            throw new \InvalidArgumentException('You must provide "minField" and "maxField" options');
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