<?php
use Mty95\NewFramework\NewRestController;

class Quote extends NewRestController
{
    protected $repository;
    protected $facade;

    public function __construct()
	{
		parent::__construct();

		$this->repository = null;
		$this->facade = null;
	}

	/**
	 * @Rest(method="GET", route="/payment-method")
	 */
    public function getPaymentMethods(): void
	{
		$this->success([
			'methods' => (new \App\Model\PaymentMethod())->getMethods()
		]);
	}

	/**
	 * @Rest(method="POST", route"/payment-method")
	 */
	public function getOrderDetailsBeforeCheckout(): void
	{
		$this->success([
			'details' => [],
			'payment_method' => [],
		]);
	}
}
