<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Exceptions\ValidationException;

class Checkout extends \Core\API\Authenticated
{
    protected $repository;
    protected $facade;

	protected $auth;
	protected $userData;
	protected $isAuthenticated = false;
	/**
	 * @var User $user
	 */
	protected $user;
	/**
	 * @var \App\Model\CustomerAddress\Repository
	 */
	private $addressRepository;

	public function __construct()
	{
		parent::__construct();

		$this->assertUserIsAuthenticated();
		$this->repository = null;
		$this->facade = null;

		$this->addressRepository = \App\Model\CustomerAddress\Repository::take();

	}

	/**
	 * @Rest(method="GET", route="/address-info")
	 */
	public function getAddressInfo(): bool
	{
		$lastAddressId = 0;
		$addresses = $this->addressRepository->ofUser($this->user)->findAll();

		foreach ($addresses as $address)
		{
			$lastAddressId = $address->id;
			break;
		}

		return $this->success([
			'addresses' => \App\Library\Collection::toExport($addresses),
			'last_address_id' => $lastAddressId,

		]);
	}

	/**
	 * @Rest(method="POST", route="/address-info")
	 */
    public function setAddressInfo(): bool
	{
		$checkout = $this->getCheckoutModel();

		try {
			$response = $checkout->setAddressInfo($this->post());
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success([
			'data' => $response,
		]);
	}

	/**
	 * @Rest(method="GET", route="/payment-method")
	 */
	public function getPaymentMethodInfo(): bool
	{
		$checkout = $this->getCheckoutModel();

		try {
			$result = $checkout->getPaymentMethods();
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success([
			'data' => $result,
		]);
	}


	/**
	 * @Rest(method="POST", route="/payment-method")
	 */
	public function processPayment(): bool
	{
		$checkout = $this->getCheckoutModel();

		try {
			$result = $checkout->processPayment($this->post());
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success($result);
	}

	/**
	 * @return \App\Model\Checkout
	 */
	private function getCheckoutModel(): \App\Model\Checkout
	{
		return Services::take(\App\Model\Checkout::class, [$this->user]);
	}
}
