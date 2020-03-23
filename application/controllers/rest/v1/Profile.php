<?php

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\UserFacade;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\AbstractRestController;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

class Profile extends \Core\API\Authenticated
{
    protected $repository;
    protected $facade;

    protected $isAuthenticated = false;

    public function __construct()
	{
		parent::__construct();

		$this->assertUserIsAuthenticated();
		$this->repository = Repository::take();
		$this->facade = new UserFacade();
	}

	/**
	 * @Rest(method="PATCH", route="/update")
	 */
    public function updateProfile(): bool
	{
		try {
			$this->facade->updateProfile($this->user, $this->patch());
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success([
			'data' => $this->user->toExport(),
		]);
	}

	/**
	 * @Rest(method="GET", route="/ping")
	 */
	public function ping(): bool
	{
		return $this->success([
			'data' => $this->user->toExport(),
		]);
	}
}
