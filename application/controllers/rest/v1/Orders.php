<?php

use App\Library\Collection;
use App\Library\Mty95\AuthorizationToken;
use App\Model\Order\Order;
use App\Model\OrderService;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\AbstractRestController;

class Orders extends \Core\API\Authenticated
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

		$this->assertUserIsAuthenticated();
		$this->repository = \App\Model\Order\Repository::take();
		$this->facade = null;
	}

	/**
	 * @Rest(method="GET", route="/")
	 */
    public function list(): void
	{
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
