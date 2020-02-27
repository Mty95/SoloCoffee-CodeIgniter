<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\User\User;

abstract class Method
{
	protected $additional_data = [];
	protected $method_name = '';
	protected $min_order_amount = 0.00;

	abstract public function execute(Cart $cart, CartAddress $address, User $user, array $data = []);

	/**
	 * @return array
	 */
	public function getAdditionalData(): array
	{
		return $this->additional_data;
	}

	/**
	 * @return string
	 */
	public function getMethodName(): string
	{
		return $this->method_name;
	}
}
