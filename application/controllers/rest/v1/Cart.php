<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\CartService;
use App\Model\Category\Category;
use App\Model\Product\Product;
use App\Model\User\Repository;
use App\Model\User\User;
use Core\API\Authenticated;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Exceptions\ValidationException;

/**
 * Class Cart
 *
 * Pending 2020-03-18:
 * https://symfonycasts.com/screencast/symfony
 * https://laracasts.com/series/whats-new-in-laravel-7
 * https://www.youtube.com/playlist?list=PLEkJYA4gJb78lIOKjZ0tJ9rWszT6uCTJH
 * https://freek.dev/1582-how-to-write-exceptionally-good-exceptions-in-php
 *
 */
class Cart extends Authenticated
{
    protected $repository;
    protected $facade;

	protected $auth;
	protected $userData;
	protected $isAuthenticated = false;

	/**
	 * @var CartService
	 */
	protected $cart;

	public function __construct()
	{
		parent::__construct();
		$this->assertUserIsAuthenticated();

		$this->repository = null;
		$this->facade = null;
		$this->cart = Services::take(CartService::class, [$this->user]);
	}

	/**
	 * @Rest(method="GET", route="/item/{slug}")
	 * @param Product $product
	 */
	public function getProduct(Product $product): void
	{
		$this->success([
			'item' => $this->cart->getItemDetails($product),
			'cart' => $this->cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="GET", route="/category/{slug}")
	 * @param Category $category
	 */
	public function getProductFromCategory(Category $category): void
	{
		$this->success([
			'cart' => $this->cart->getDetails(),
			'category' => $category->toExport(),
			'products' => $this->cart->getItemsFromCategory($category),
		]);
	}

	/**
	 * @Rest(method="GET", route="/")
	 */
	public function showDetails(): void
	{
		$this->success([
			'cart' => $this->cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="PUT", route="/")
	 */
    public function addToCart(): void
	{
		try {
			$product = $this->cart->addItem($this->put());
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $this->cart->errors()]);
			return;
		}

		$this->success([
			'product' => $this->cart->getItemDetails($product),
			'cart' => $this->cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="PATCH", route="/")
	 */
	public function updateCart(): void
	{
		try {
			$product = $this->cart->updateItem($this->patch());
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $this->cart->errors()]);
			return;
		}

		$this->success([
			'product' => $this->cart->getItemDetails($product),
			'cart' => $this->cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="DELETE", route="/{slug}")
	 *
	 * @param Product $product
	 */
	public function removeFromCart(Product $product): void
	{
		try {
			$this->cart->removeItem($product);
		} catch (Exception $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $this->cart->errors()]);
			return;
		}

		$this->success([
			'cart' => $this->cart->getDetails(),
		]);
	}
}
