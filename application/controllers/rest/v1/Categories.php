<?php

use App\Library\Collection;
use App\Library\Mty95\AuthorizationToken;
use App\Model\Category\Category;
use App\Model\Category\CategoryFacade;
use App\Model\Category\Repository;
use App\Model\User\User;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Entity;

class Categories extends \Core\API\Authenticated
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
	 * @var \App\Model\CartService
	 */
	protected $cart;

    public function __construct()
	{
		parent::__construct();
		$this->assertUserIsAuthenticated();
		$this->repository = Repository::take();
		$this->facade = new CategoryFacade();


		$this->cart = \App\Services::take(\App\Model\CartService::class, [$this->user]);
	}

	/**
	 * @Rest(method="GET", route="/")
	 */
    public function list(): bool
	{
		$this->success([
			'categories' => Collection::toExport($this->repository->findAll()),
			'cart' => $this->cart->getDetails(),
		]);
	}

	/**
	 * @Rest(method="GET", route="/slug/{slug}")
	 * @param Category $category
	 * @return bool
	 */
    public function listByCategory(Category $category): bool
	{
		$productRepository = \App\Model\Product\Repository::take();
		$products = $productRepository->getByCategory($category);

		return $this->success([
			'category' => $category->toExport(),
			'products' => $this->cart->getProductsDetails($products),
//			'products' => Collection::toExport($products),
		]);
	}

	/**
	 * @Rest(method="GET", route="/{id}")
	 *
	 * @param Entity $entity
	 * @return bool
	 */
    public function show(Entity $entity): bool
	{
        return $this->success([
            'entity' => $entity->toArray(),
        ]);
	}
}
