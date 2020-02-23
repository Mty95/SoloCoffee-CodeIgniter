<?php

use Mty95\NewFramework\Validation\MyFormValidation;

class MY_Form_validation extends MyFormValidation
{
    public function __construct(array $rules = array())
    {
        parent::__construct($rules);
    }

    public function valid_code($str, $params): bool
    {
        $params = $params === '' ? [] : explode(',', $params);
        $minLetter = $params[0] ?? 1;
        $minNumber = $params[1] ?? 1;

        $this->set_message('valid_code', "El campo %s debe tener al menos {$minLetter} letra y {$minNumber} número.");

        return (bool) preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])([a-zA-Z0-9]+)$/', $str);
    }



	/**
	 * @deprecated Under Development
	 *
	 * Function
	 *
	 * Calls a function of determined class.
	 * function/method need to be static
	 *
	 * @param string $value
	 * @param string $params
	 * @return bool
	 */
    public function function(string $value, string $params): bool
	{
		sscanf($params, '%[^.].%[^.]', $className, $method);

		return true;
    }
}
