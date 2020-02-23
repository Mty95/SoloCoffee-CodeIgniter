<?php

use App\Library\Collection;
use App\Model\Product\Product;
use Mty95\NewFramework\NewRestController;

class Products extends NewRestController
{
    protected $repository;
    protected $facade;

    public function __construct()
	{
		parent::__construct();

		$this->repository = \App\Model\Product\Repository::take();
		$this->facade = new \App\Model\Product\ProductFacade();
	}

	/**
	 * @Rest(method="GET", route="/", enabled=false)
	 */
    public function list(): void
	{
		$this->success([
			'data' => Collection::toArray($this->repository->findAll())
		]);
	}

	/**
	 * @Rest(method="GET", route="/{slug}")
	 *
	 * @param Product $entity
	 */
    public function show(Product $entity): void
	{
        $this->success([
            'entity' => $entity->toExport(),
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
