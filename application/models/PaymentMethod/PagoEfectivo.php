<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\CustomerAddress\CustomerAddress;
use App\Model\Order\Order;
use App\Model\User\User;
use Culqi\Charges;
use NewFramework\Logger;

class PagoEfectivo extends Method
{
	const METHOD_NAME = 'pago_efectivo';

	protected $additional_data = [];
	protected $method_name = self::METHOD_NAME;
	protected $method_title = 'PagoEfectivo';
	protected $can_processing = false;
	protected $is_offline = true;
	protected $currencies_supported = ['PEN'];

	/**
	 * @var array
	 */
	private $config;

	public function __construct()
	{
		$this->config = get_config()['payment_methods'][self::METHOD_NAME];
	}

	public function execute(Cart $cart, CustomerAddress $address, User $user, array $data = []): PagoEfectivo
	{
		return $this;
	}

	public function executeOffline(Order $order, CustomerAddress $address, User $user, array $data = []): PagoEfectivo
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
