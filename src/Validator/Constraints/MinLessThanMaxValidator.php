<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MinLessThanMaxValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint MinLessThanMax */

        $minField = $object->{$constraint->minField};
        $maxField = $object->{$constraint->maxField};

        if (!is_array($minField) || !is_array($maxField)) {
            return;
        }

        $count = min(count($minField), count($maxField));

        for ($i = 0; $i < $count; $i++) {
            if ($minField[$i] > $maxField[$i]) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('{{ min }}', (string) $minAmounts[$i])
                    ->setParameter('{{ max }}', (string) $maxAmounts[$i])
                    ->setParameter('{{ index }}', (string) $i)
                    ->atPath(sprintf('%s[%d]', $constraint->minField, $i))
                    ->addViolation()
                ;
            }
        }
    }
}