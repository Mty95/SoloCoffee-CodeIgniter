<?php

use App\Library\Collection;
use App\Library\Mty95\AuthorizationToken;
use App\Model\Order\Order;
use App\Model\OrderService;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\NewRestController;

class Orders extends NewRestController
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

		$this->repository = \App\Model\Order\Repository::take();
		$this->facade = null;

		$this->auth = new AuthorizationToken();
		$this->userData = $this->auth->userData();

		if (!isset($this->userData->status) && isset($this->userData->id))
		{
			$this->isAuthenticated = true;
			$userRepository = Repository::take();
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
		$orders = $this->repository->where('user_id', $this->user->id)->order('DESC')->findAll();

		$this->success([
			'orders' => Collection::toExport($orders)
		]);
	}

	/**
	 * @Rest(method="GET", route="/{increment_id}")
	 *
	 * @param Order $order
	 */
    public function show(Order $order): void
	{
		$this->assertUserIsAuthenticated();

		if ($order->user_id !== $this->user->id)
		{
			$this->fail(['message' => 'User can not access to this order.', $order->user_id, $this->user->id]);
			return;
		}

		/** @var OrderService $service */
		$service = Services::take(OrderService::class);

        $this->success($service->getDetails($order));
	}
}
