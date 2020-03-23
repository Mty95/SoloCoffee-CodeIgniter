<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Exceptions\ValidationException;

class Checkout extends AbstractRestController
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

    public function __construct()
	{
		parent::__construct();

		$this->repository = null;
		$this->facade = null;

		$this->auth = new AuthorizationToken();
		$this->userData = $this->auth->userData();

		if (!isset($this->userData->status) && isset($this->userData->id))
		{
			$this->isAuthenticated = true;
			$userRepository = Repository::take();
			$this->user = $userRepository->find($this->userData->id);
		}
	}

	private function assertUserIsAuthenticated(): void
	{
		if (!$this->isAuthenticated)
		{
			$this->fail(['message' => 'User not authenticated.']);
			return;
		}
	}

	/**
	 * @Rest(method="GET", route="/address-info")
	 */
	public function getAddressInfo(): void
	{
		$this->assertUserIsAuthenticated();
		/*
		$checkout = $this->getCheckoutModel();
		$lastAddress = $checkout->getLastAddressInfo();
		try {
			$response = $checkout->getAddressInfo();
		} catch (Exception $e) {
			$this->fail([
				'message' => $e->getMessage(),
				'last_address' => $lastAddress,
			]);
			return;
		}*/

		$lastAddressId = 0;
		$addresses = \App\Model\CustomerAddress\Repository::take()->where('user_id', $this->user->id)->findAll();
		foreach ($addresses as $address)
		{
			$lastAddressId = $address->id;
			break;
		}

		$this->success([
//			'address_data' => $response,
//			'last_address' => $lastAddress,
			'addresses' => \App\Library\Collection::toExport($addresses),
			'last_address_id' => $lastAddressId,

		]);
	}

	/**
	 * @Rest(method="POST", route="/address-info")
	 */
    public function setAddressInfo(): void
	{
		$this->assertUserIsAuthenticated();
		$checkout = $this->getCheckoutModel();
		try {
			$response = $checkout->setAddressInfo($this->post());
		} catch (ValidationException $e) {
			$this->fail([
				'message' => $e->getMessage(),
				'errors' => $checkout->errors(),
			]);
			return;
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage()]);
			return;
		}

		$this->success([
			'data' => $response,
		]);
	}

	/**
	 * @Rest(method="GET", route="/payment-method")
	 */
	public function getPaymentMethodInfo(): void
	{
		$this->assertUserIsAuthenticated();
		$checkout = $this->getCheckoutModel();

		try {
			$result = $checkout->getPaymentMethods();
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage()]);
			return;
		}

		$this->success([
			'data' => $result,
		]);
	}


	/**
	 * @Rest(method="POST", route="/payment-method")
	 */
	public function processPayment(): void
	{
		$this->assertUserIsAuthenticated();
		$checkout = $this->getCheckoutModel();

		try {
			$result = $checkout->processPayment($this->post());
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage()]);
			return;
		}

		$this->success($result);
	}

	/**
	 * @return \App\Model\Checkout
	 */
	private function getCheckoutModel(): \App\Model\Checkout
	{
		return Services::take(\App\Model\Checkout::class, [$this->user]);
	}
}
