<?php

use App\Library\Collection;
use App\Model\Product\Product;
use Mty95\NewFramework\AbstractRestController;

class Products extends \Core\API\Authenticated
{
    protected $repository;
    protected $facade;

	protected $isAuthenticated = false;

	/**
	 * @var \App\Model\CartService
	 */
	protected $cart;

    public function __construct()
	{
		parent::__construct();
		$this->assertUserIsAuthenticated();
		$this->repository = \App\Model\Product\Repository::take();
		$this->facade = new \App\Model\Product\ProductFacade();
		$this->cart = Services::take(\App\Model\CartService::class, [$this->user]);
	}

	/**
	 * @Rest(method="GET", route="/", enabled=false)
	 */
    public function list(): bool
	{
		$this->success([
			'data' => $this->cart->getProductsDetails($this->repository->findAll())
		]);
	}

	/**
	 * @Rest(method="GET", route="/{slug}")
	 *
	 * @param Product $product
	 * @return bool
	 */
    public function show(Product $product): bool
	{
        $this->success([
            'product' => $this->cart->getProductDetails($product),
        ]);
	}
}
