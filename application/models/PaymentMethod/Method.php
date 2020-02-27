<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\Order\Order;
use App\Model\User\User;

abstract class Method
{
	protected $additional_data = [];
	protected $method_name = '';
	protected $min_order_amount = 0.00;
	protected $can_processing = false;
	protected $is_offline = false;
	protected $currencies_supported = [];

	abstract public function execute(Cart $cart, CartAddress $address, User $user, array $data = []);
	abstract public function executeOffline(Order $cart, CartAddress $address, User $user, array $data = []);

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

	/**
	 * @return bool
	 */
	public function isCanProcessing(): bool
	{
		return $this->can_processing;
	}

	public function isOffline(): bool
	{
		return $this->is_offline;
	}
}
