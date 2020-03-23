<?php
namespace Core\API;

use App\Library\Mty95\AuthorizationToken;
use App\Model\User\Repository;
use App\Model\User\User;
use NewFramework\RestController;

class Authenticated extends RestController
{
	protected $repository;
	protected $facade;

	protected $auth;
	protected $userData;
	protected $isAuthenticated = false;

	/**
	 * @var User
	 */
	protected $user;

	public function __construct()
	{
		parent::__construct();

		$this->auth = new AuthorizationToken();
		$this->userData = $this->auth->userData();

		if (!isset($this->userData->status) && isset($this->userData->id))
		{
			$this->isAuthenticated = true;
			$userRepository = Repository::take();
			$this->user = $userRepository->find($this->userData->id);
		}
	}

	protected function assertUserIsAuthenticated(): void
	{
		if (!$this->isAuthenticated)
		{
			$this->fail(['message' => 'User not authenticated.']);
			return;
		}
	}
}
