<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ArraysEqualLengthValidator extends ConstraintValidator
{
    public function validate($object, Constraint $constraint)
    {
        /* @var $constraint ArraysEqualLength */

        for ($i = 1; $i < count($constraint->arrayFields); $i++) {
            $current = $object->{$constraint->arrayFields[$i]};
            $prev = $object->{$constraint->arrayFields[$i - 1]};
            
            if (!is_array($prev) || !is_array($current)) {
                return;
            }

            if (count($prev) != count($current)) {
                $this->context
                    ->buildViolation($constraint->message)
                    ->setParameter('{{ arr1 }}', $constraint->arrayFields[$i - 1])
                    ->setParameter('{{ arr2 }}', $constraint->arrayFields[$i])
                    ->addViolation()
                ;
            }
        }
    }
}