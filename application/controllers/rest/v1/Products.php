<?php

use App\Library\Collection;
use App\Model\Product\Product;
use Mty95\NewFramework\AbstractRestController;

class Products extends AbstractRestController
{
    protected $repository;
    protected $facade;

	protected $auth;
	protected $userData;
	protected $isAuthenticated = false;
	/**
	 * @var \App\Model\User\User $user
	 */
	protected $user;

    public function __construct()
	{
		parent::__construct();

		$this->repository = \App\Model\Product\Repository::take();
		$this->facade = new \App\Model\Product\ProductFacade();

		$this->auth = new \App\Library\Mty95\AuthorizationToken();
		$this->userData = $this->auth->userData();

		if (!isset($this->userData->status) && isset($this->userData->id))
		{
			$this->isAuthenticated = true;
			$userRepository = \App\Model\User\Repository::take();
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
	 * @Rest(method="GET", route="/", enabled=false)
	 */
    public function list(): void
	{
		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

		$this->success([
			'data' => $cart->getProductsDetails($this->repository->findAll())
		]);
	}

	/**
	 * @Rest(method="GET", route="/{slug}")
	 *
	 * @param Product $product
	 */
    public function show(Product $product): void
	{
		/** @var \App\Model\Cart $cart */
		$cart = Services::take(\App\Model\Cart::class, [$this->user]);

        $this->success([
            'product' => $cart->getProductDetails($product),
        ]);
	}

	/**
	 * @Rest(method="POST", route="/", enabled=false)
	 *
	 * @param Product $entity
	 */
    public function create(): void
	{
		$entity = new Product();
		$entity->fill((array) $this->post());
		$this->repository->save($entity);

		$this->success([
            'entity_id' => $entity->id,
        ]);
	}

	/**
	 * @Rest(method="PUT", route="/{id}", enabled=false)
	 *
	 * @param Product $entity
	 */
    public function update(Product $entity): void
	{
		$entity->fill((array) $this->post());
		$this->repository->save($entity);

		$this->success([
            'entity' => $entity->toArray(),
        ]);
	}

	/**
	 * @Rest(method="DELETE", route="/{id}", enabled=false)
	 *
	 * @param Product $entity
	 */
    public function remove(Product $entity): void
	{
        $this->repository->delete($entity);

        $this->success(['method' => 'remove']);
	}
}
