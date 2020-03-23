<?php
namespace App\Model;

use App\Model\PaymentMethod\BankTransfer;
use App\Model\PaymentMethod\CashOnDelivery;
use App\Model\PaymentMethod\Culqi;
use App\Model\PaymentMethod\Method;
use App\Model\PaymentMethod\PagoEfectivo;

class PaymentMethod
{
	/**
	 * @var array
	 */
	private $config;

	private static $gateways = [
		Culqi::METHOD_NAME => Culqi::class,
		PagoEfectivo::METHOD_NAME => PagoEfectivo::class,
		BankTransfer::METHOD_NAME => BankTransfer::class,
		CashOnDelivery::METHOD_NAME => CashOnDelivery::class,
	];

	public function __construct()
	{
		$this->config = get_config()['payment_methods'];
	}

	public static function getByName(string $paymentMethod):? Method
	{
		if (!isset(self::$gateways[$paymentMethod]))
		{
			return null;
		}

		/** @var Method $method */
		$className = self::$gateways[$paymentMethod];
		$method = new $className();

		return $method;
	}

	public static function getStatusLabel($status): string
	{
		$statues = [
			'pending' => 'Pendiente',
		];

		return $statues[$status];
	}

	public function getMethods(\App\Model\Cart\Cart $cart): array
	{
		$methods = [];

		$methods[] = [
			'code' => 'cod',
			'title' => 'Pago contra entrega',
			'message' => 'El tiempo máx. para recibir tu orden es de 3-4h.',
		];
		$methods[] = [
			'code' => 'bank_transfer',
			'title' => 'Transferencia bancaria',
			'message' => 'Nro de cuenta: 888-0000-0000-00. Tiempo máx. de verificación: 1h.',
		];

		if ($cart->total >= 3)
		{
			$methods[] = [
				'code' => 'culqi',
				'title' => 'Culqi (Visa, MC, Amex)',
				'public_key' => $this->config['culqi']['public_key'],
				'amount' => $cart->total,
			];
		}

		/*$methods[] = [
			'code' => 'pago_efectivo',
			'title' => 'PagoEfectivo',
		];*/

		return $methods;
	}
}
