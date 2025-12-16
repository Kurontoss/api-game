<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AddToOne extends Constraint
{
    public string $message = 'Elements of array add up to {{ sum }} instead of 1';

    public function getTargets(): string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}