<?php
namespace App\Validator;

class Constraints
{
    public function validatedBy()
	{
	    return get_class($this).'Validator';
	}
}