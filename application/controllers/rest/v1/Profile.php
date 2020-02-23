<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\UserFacade;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\NewRestController;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

class Profile extends NewRestController
{
    protected $repository;
    protected $facade;

    protected $auth;
    protected $userData;
    protected $isAuthenticated = false;
    protected $user;

    public function __construct()
	{
		parent::__construct();

		$this->repository = Repository::take();
		$this->facade = new UserFacade();
		$this->auth = new AuthorizationToken();
		$this->userData = $this->auth->userData();

		if (!isset($this->userData->status) && isset($this->userData->id))
		{
			$this->isAuthenticated = true;
			$this->user = $this->repository->find($this->userData->id);
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
	 * @Rest(method="PATCH", route="/update")
	 */
    public function updateProfile(): void
	{
		try {
			$this->facade->updateProfile($this->user, $this->patch());
		} catch (ValidationException $e) {
			$this->fail([
				'message' => $e->getMessage(),
				'errors' => $this->facade->errors(),
			]);
			return;
		} catch (DataException | EntityException $e) {
			$this->fail([
				'message' => $e->getMessage(),
			]);
		}

		$this->success([
			'data' => $this->user->toExport(),
		]);
	}

	/**
	 * @Rest(method="GET", route="/ping")
	 */
	public function ping(): void
	{
		$this->success([
			'data' => $this->user->toExport(),
		]);
	}
}
