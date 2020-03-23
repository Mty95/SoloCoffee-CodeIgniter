<?php

use App\Library\Collection;
use App\Library\Mty95\AuthorizationToken;
use App\Model\Category\Category;
use App\Model\Category\CategoryFacade;
use App\Model\Category\Repository;
use App\Model\User\User;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Entity;

class Categories extends AbstractRestController
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

		$this->repository = Repository::take();
		$this->facade = new CategoryFacade();

		$this->auth = new AuthorizationToken();
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
	 * @Rest(method="GET", route="/")
	 */
    public function list(): void
	{
		$this->assertUserIsAuthenticated();

		/** @var \App\Model\CartService $cart */
		$cart = \App\Services::take(\App\Model\CartService::class, [$this->user]);

		$this->success([
			'categories' => Collection::toExport($this->repository->findAll()),
			'cart' => $cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="GET", route="/slug/{slug}")
	 * @param Category $category
	 */
    public function listByCategory(Category $category): void
	{
		/** @var \App\Model\CartService $cart */
		$cart = Services::take(\App\Model\CartService::class, [$this->user]);

		$productRepository = \App\Model\Product\Repository::take();
		$products = $productRepository->getByCategory($category);

		$this->success([
			'category' => $category->toExport(),
			'products' => $cart->getProductsDetails($products),
//			'products' => Collection::toExport($products),
		]);
	}

	/**
	 * @Rest(method="GET", route="/{id}")
	 *
	 * @param Entity $entity
	 */
    public function show(Entity $entity): void
	{
        $this->success([
            'entity' => $entity->toArray(),
        ]);
	}

	/**
	 * @Rest(method="POST", route="/")
	 *
	 * @param Entity $entity
	 */
    public function create(): void
	{
		$entity = new Entity();
		$entity->fill((array) $this->post());
		$this->repository->save($entity);

		$this->success([
            'entity_id' => $entity->id,
        ]);
	}

	/**
	 * @Rest(method="PUT", route="/{id}")
	 *
	 * @param Entity $entity
	 */
    public function update(Entity $entity): void
	{
		$entity->fill((array) $this->post());
		$this->repository->save($entity);

		$this->success([
            'entity' => $entity->toArray(),
        ]);
	}

	/**
	 * @Rest(method="DELETE", route="/{id}")
	 *
	 * @param Entity $entity
	 */
    public function remove(Entity $entity): void
	{
        $this->repository->delete($entity);

        $this->success(['method' => 'remove']);
	}
}
