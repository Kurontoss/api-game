<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AddToOneValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint AddToOne */

        if (abs(array_sum($object) - 1) > 0.00001) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ sum }}', array_sum($object))
                ->addViolation()
            ;
        }
    }
}