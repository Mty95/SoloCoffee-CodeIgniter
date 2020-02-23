<?php
namespace App\Model;

class PaymentMethod
{
	public function __construct()
	{
	}

	public function getMethods(): array
	{
		return [
			[
				'code' => 'cod',
				'title' => 'Pago contra entrega',
				'message' => 'Recargo de S/ 5.00 a tu pedido',
			],
			[
				'code' => 'bank_transfer',
				'title' => 'Transferencia bancaria',
				'message' => 'Nro de cuenta: 888-0000-0000-00. Tiempo máx. de verificación: 2h.',
			],
			[
				'code' => 'culqi',
				'title' => 'Culqi (Visa, MC, Amex)',
				'public_key' => '',
			],
			[
				'code' => 'pago_efectivo',
				'title' => 'PagoEfectivo',
			],
		];
	}
}
