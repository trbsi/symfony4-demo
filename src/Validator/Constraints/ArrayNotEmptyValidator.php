<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ArrayNotEmptyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (empty($value->getValues())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}