<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\CustomerAddress\CustomerAddress;
use App\Model\Order\Order;
use App\Model\User\User;
use Culqi\Charges;
use NewFramework\Logger;

class BankTransfer extends Method
{
	const METHOD_NAME = 'bank_transfer';

	protected $additional_data = [];
	protected $method_name = self::METHOD_NAME;
	protected $method_title = 'Transferencia Bancaria';
	protected $can_processing = false;
	protected $is_offline = true;
	protected $currencies_supported = ['PEN'];

	/**
	 * @var array
	 */
	private $config;

	public function __construct()
	{
		$methodsConfig = get_config()['payment_methods'];
		$this->config = $methodsConfig[self::METHOD_NAME] ?? [];
	}

	public function execute(Cart $cart, CustomerAddress $address, User $user, array $data = []): BankTransfer
	{
		return $this;
	}

	public function executeOffline(Order $order, CustomerAddress $address, User $user, array $data = []): BankTransfer
	{
		$this->additional_data = ['instructions' => 'Nro de cuenta: 888-0000-0000-00. Tiempo máx. de verificación: 1h.'];

		return $this;
	}
}
