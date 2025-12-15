<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class EntityExists extends Constraint
{
    public string $message = 'Entity {{ id }} does not exist.';

    public string $entityClass;

    public function __construct(
        string $entityClass,
        string $message = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(groups: $groups, payload: $payload);

        $this->entityClass = $entityClass;
    }

    public function getRequiredOptions(): array
    {
        return ['entityClass'];
    }

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}