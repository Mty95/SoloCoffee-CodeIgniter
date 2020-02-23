<?php

namespace App\Exceptions;

use App\Services;
use Mty95\DebugBar\NewFrameworkDebugBar;
use Throwable;

class ValidationFieldException extends \Exception
{
	protected $field = '';

	private function __construct($message = '')
	{
		parent::__construct($message);

		/** @var NewFrameworkDebugBar $debugBar */
		$debugBar = Services::getShared(NewFrameworkDebugBar::class);
		$debugBar['exceptions']->addThrowable($this);
	}

	private function setField(string $field): void
	{
		$this->field = $field;
	}

	public function getField(): string
	{
		return $this->field;
	}

	public static function notValid(string $message, string $field): ValidationFieldException
	{
		$exception = new static($message);
		$exception->setField($field);

		return $exception;
	}
}
