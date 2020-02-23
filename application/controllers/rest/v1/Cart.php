<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\Product\Product;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\NewRestController;
use NewFramework\Exceptions\ValidationException;

class Cart extends NewRestController
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
	 * @Rest(method="GET", route="/")
	 */
	public function showDetails(): void
	{
		$this->assertUserIsAuthenticated();

		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

		$result = $cart->getDetails();

		$this->success([
			'details' => $result,
		]);
	}

	/**
	 * @Rest(method="PUT", route="/")
	 */
    public function addToCart(): void
	{
		$this->assertUserIsAuthenticated();

		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

		try {
			$result = $cart->addItem($this->put());
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $cart->errors()]);
			return;
		}

		$this->success([
			'details' => $result,
		]);
	}

	/**
	 * @Rest(method="PATCH", route="/")
	 */
	public function updateCart(): void
	{
		$this->assertUserIsAuthenticated();

		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

		try {
			$result = $cart->updateItem($this->patch());
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $cart->errors()]);
			return;
		}

		$this->success([
			'details' => $result,
		]);
	}

	/**
	 * @Rest(method="DELETE", route="/{slug}")
	 *
	 * @param Product $product
	 */
	public function removeFromCart(Product $product): void
	{
		$this->assertUserIsAuthenticated();
		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

		try {
			$result = $cart->removeItem($product);
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $cart->errors()]);
			return;
		}

		$this->success([
			'details' => $result,
		]);
	}
}
