<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\Order\Order;
use App\Model\User\User;
use Culqi\Charges;
use NewFramework\Logger;

class PagoEfectivo extends Method
{
	protected $additional_data = [];
	protected $method_name = 'pago_efectivo';
	protected $can_processing = false;
	protected $is_offline = true;
	protected $currencies_supported = ['PEN'];

	/**
	 * @var array
	 */
	private $config;

	public function __construct()
	{
		$this->config = get_config()['payment_methods']['pago_efectivo'];
	}

	public function execute(Cart $cart, CartAddress $address, User $user, array $data = []): PagoEfectivo
	{
		return $this;
	}

	public function executeOffline(Order $order, CartAddress $address, User $user, array $data = []): PagoEfectivo
	{
		$client = new PagoEfectivoClient(
			PagoEfectivoClient::MODE_INTEGRATION,
			$this->config
		);
		$authData = $client->authorization();
		$response = $client->generateCip($order, $address, $user, (array) $authData['data']);

		$this->additional_data = $response['data'];

		return $this;
	}
}
