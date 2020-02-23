<?php

use App\Exceptions\ValidationFieldException;
use App\Library\Collection;
use App\Model\User\UserFacade;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\NewRestController;
use NewFramework\Entity;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

/**
 * Class Authentication
 *
 * https://i.pinimg.com/originals/db/b6/dd/dbb6ddccf60fc8f4db3072f3e10fec43.png
 * https://www.pinterest.com/pin/450852612686704550/
 * 
 */
class Authentication extends NewRestController
{
    protected $repository;
    protected $facade;

    public function __construct()
	{
		parent::__construct();

		$this->repository = null;
		$this->facade = null;
	}

	/**
	 * @Rest(method="POST", route="/login")
	 */
	public function login(): void
	{
		$facade = new UserFacade();

		try {
			$user = $facade->login((array)$this->post());
		} catch (ValidationFieldException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => [$e->getField() => $e->getMessage()]]);
			return;
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $facade->errors()]);
			return;
		}

		$authToken = new \App\Library\Mty95\AuthorizationToken();

		$this->success([
			'user_details' => $user->toArray(),
			'username' => $user->username,
			'auth_token' => $authToken->generateUserToken($user->id),
		]);
	}

	/**
	 * @Rest(method="POST", route="/register")
	 */
	public function register(): void
	{
		$facade = new UserFacade();

		try {
			$user = $facade->register((array)$this->post());
		} catch (DataException | EntityException $e) {
			$this->fail(['message' => $e->getMessage()]);
			return;
		} catch (ValidationException $e) {
			$this->fail(['message' => $e->getMessage(), 'errors' => $facade->errors()]);
			return;
		}

		$this->success([
			'message' => 'User successful registered.',
			'username' => $user->username,
		]);
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
	 * @Rest(method="GET", route="/{id}", enabled=false)
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
	 * @Rest(method="POST", route="/", enabled=false)
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
	 * @Rest(method="PUT", route="/{id}", enabled=false)
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
	 * @Rest(method="DELETE", route="/{id}", enabled=false)
	 *
	 * @param Entity $entity
	 */
    public function remove(Entity $entity): void
	{
        $this->repository->delete($entity);

        $this->success(['method' => 'remove']);
	}
}
