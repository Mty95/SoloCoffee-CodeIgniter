<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\NewRestController;
use NewFramework\Exceptions\ValidationException;

class Checkout extends NewRestController
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

		/** @var \App\Model\Checkout $checkout */
		$checkout = Services::take(\App\Model\Checkout::class, [$this->user]);
		try {
			$response = $checkout->getAddressInfo();
		} catch (Exception $e) {
			$this->fail([
				'message' => $e->getMessage(),
			]);
			return;
		}

		$this->success([
			'address_data' => $response,
		]);
	}

	/**
	 * @Rest(method="POST", route="/address-info")
	 */
    public function setAddressInfo(): void
	{
		$this->assertUserIsAuthenticated();

		/** @var \App\Model\Checkout $checkout */
		$checkout = Services::take(\App\Model\Checkout::class, [$this->user]);
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

		/** @var \App\Model\Checkout $checkout */
		$checkout = Services::take(\App\Model\Checkout::class, [$this->user]);

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

		/** @var \App\Model\Checkout $checkout */
		$checkout = Services::take(\App\Model\Checkout::class, [$this->user]);

		try {
			$result = $checkout->processPayment($this->post());
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage()]);
			return;
		}

		$this->success($result);
	}
}
