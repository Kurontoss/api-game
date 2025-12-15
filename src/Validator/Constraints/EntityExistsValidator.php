<?php

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {}
    
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint EntityExists */

        if (!$value) {
            return;
        }

        $entity = $this->em
            ->getRepository($constraint->entityClass)
            ->find($value)
        ;

        if (!$entity) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ id }}', $value)
                ->addViolation()
            ;
        }
    }
}