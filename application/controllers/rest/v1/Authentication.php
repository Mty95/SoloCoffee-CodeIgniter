<?php

use App\Exceptions\ValidationFieldException;
use App\Library\Collection;
use App\Model\User\UserFacade;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\AbstractRestController;
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
class Authentication extends \NewFramework\RestController
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
	public function login(): bool
	{
		$facade = new UserFacade();

		try {
			$user = $facade->login((array)$this->post());
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		$authToken = new \App\Library\Mty95\AuthorizationToken();

		return $this->success([
			'user_details' => $user->toExport(),
			'username' => $user->username,
			'auth_token' => $authToken->generateUserToken($user->id),
		]);
	}

	/**
	 * @Rest(method="POST", route="/register")
	 */
	public function register(): bool
	{
		$facade = new UserFacade();

		try {
			$user = $facade->register((array)$this->post());
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success([
			'message' => 'User successful registered.',
			'username' => $user->username,
		]);
	}
}
