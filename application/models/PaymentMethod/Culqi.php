<?php
namespace App\Model\PaymentMethod;

use App\Model\Cart\Cart;
use App\Model\CartAddress\CartAddress;
use App\Model\CustomerAddress\CustomerAddress;
use App\Model\Order\Order;
use App\Model\User\User;
use Culqi\Charges;
use NewFramework\Logger;

class Culqi extends Method
{
	const METHOD_NAME = 'culqi';

	protected $additional_data = [];
	protected $method_name = self::METHOD_NAME;
	protected $method_title = 'Culqi';
	protected $can_processing = true;
	protected $is_offline = false;
	protected $currencies_supported = ['PEN'];

	/**
	 * @var array
	 */
	private $config;

	public function __construct()
	{
		$this->config = get_config()['payment_methods'][self::METHOD_NAME];
	}

	public function execute(Cart $cart, CustomerAddress $address, User $user, array $data = []): Culqi
	{
		if (!isset($data['token']))
		{
			throw new \Exception('You need to send a valid token');
		}

		$requestData = [
			'amount' => (int) $cart->total * 100,
			'capture' => true,
			'currency_code' => 'PEN',
			'description' => 'Venta de prueba',
			'email' => $user->email,
			'installments' => 0,
			'antifraud_details' => [
				'address' => $address->line1 . ' ' . $address->line2,
				'address_city' => 'LIMA',
				'country_code' => 'PE',
				'first_name' => $user->first_name,
				'last_name' => $user->last_name,
				'phone_number' => $address->cellphone,
			],
			'source_id' => $data['token'],
		];

		try {
			$culqi = new \Culqi\Culqi(['api_key' => $this->config['private_key']]);
			$charge = $culqi->Charges->create($requestData);
			Logger::write('culqi_charge', json_encode($charge));
		} catch (\Exception $e) {
			$error = json_decode($e->getMessage(), true);

			throw new \Exception(
				$error->user_message ?? 'Error al momento de capturar el pago'
			);
		}

		$this->additional_data = [
			'pan' => $charge->source->card_number,
			'date_formatted' => $charge->description,
			'currency' => $charge->currency_code,
			'card_brand' => $charge->source->iin->card_brand,
			'issuer_name' => $charge->source->iin->issuer->name,
			'reference_code' => $charge->source->iin->bin,
		];

		return $this;
	}

	public function executeOffline(Order $cart, CustomerAddress $address, User $user, array $data = [])
	{
		return $this;
	}
}
