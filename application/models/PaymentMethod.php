<?php
namespace App\Model;

class PaymentMethod
{
	/**
	 * @var array
	 */
	private $config;

	public function __construct()
	{
		$this->config = get_config()['payment_methods'];
	}

	public function getMethods(\App\Model\Cart\Cart $cart): array
	{
		$methods = [];

		$methods[] = [
			'code' => 'cod',
			'title' => 'Pago contra entrega',
			'message' => 'Recargo de S/ 5.00 a tu pedido',
		];
		$methods[] = [
			'code' => 'bank_transfer',
			'title' => 'Transferencia bancaria',
			'message' => 'Nro de cuenta: 888-0000-0000-00. Tiempo máx. de verificación: 2h.',
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

		$methods[] = [
			'code' => 'pago_efectivo',
			'title' => 'PagoEfectivo',
		];

		return $methods;
	}
}
