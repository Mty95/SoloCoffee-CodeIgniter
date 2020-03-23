<?php
use Mty95\NewFramework\AbstractRestController;

class Quote extends \Core\API\Authenticated
{
    protected $repository;
    protected $facade;

	/**
	 * @var \App\Model\CartService
	 */
    protected $cart;

    public function __construct()
	{
		parent::__construct();

		$this->assertUserIsAuthenticated();
		$this->repository = null;
		$this->facade = null;
		$this->cart = Services::take(\App\Model\CartService::class, [$this->user]);
	}

	/**
	 * @Rest(method="GET", route="/payment-method")
	 */
    public function getPaymentMethods(): bool
	{
		return $this->success([
			'methods' => (new \App\Model\PaymentMethod())->getMethods($this->cart)
		]);
	}

	/**
	 * @deprecated
	 * @Rest(method="POST", route"/payment-method", enabled="false")
	 */
	public function getOrderDetailsBeforeCheckoutDeprecated(): bool
	{
		return $this->success([
			'details' => [],
			'payment_method' => [],
		]);
	}
}
