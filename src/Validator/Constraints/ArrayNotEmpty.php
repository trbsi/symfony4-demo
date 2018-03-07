<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ArrayNotEmpty extends Constraint
{
    public $message = 'This field is required';
}
