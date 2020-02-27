<?php
namespace App\Model;

use App\Library\Collection;
use App\Model\CartAddress\CartAddress;
use App\Model\Order\Order;
use App\Model\Order\Repository;
use App\Model\OrderAddress\OrderAddress;
use App\Model\OrderItem\OrderItem;
use App\Model\PaymentMethod\Method;

class OrderService
{

	/**
	 * @var Cart\Repository
	 */
	private $cartRepository;
	/**
	 * @var Repository
	 */
	private $orderRepository;
	/**
	 * @var OrderAddress\Repository
	 */
	private $addressRepository;
	/**
	 * @var OrderItem\Repository
	 */
	private $itemRepository;

	public function __construct(
		\App\Model\Cart\Repository $cartRepository,
		Repository $orderRepository,
		\App\Model\OrderAddress\Repository $addressRepository,
		\App\Model\OrderItem\Repository $itemRepository
	)
	{
		$this->cartRepository = $cartRepository;
		$this->orderRepository = $orderRepository;
		$this->addressRepository = $addressRepository;
		$this->itemRepository = $itemRepository;
	}

	public function getDetails(Order $order): array
	{
		$address = $this->addressRepository->where('order_id', $order->id)->get();
		$items = $this->itemRepository->where('order_id', $order->id)->findAll();

		return [
			'order' => $order->toExport(),
			'address' => $address->toExport(),
			'payment' => $order->payment_data,
			'items' => Collection::toExport($items),
		];
	}

	public function createOrder(\App\Model\Cart\Cart $cart, CartAddress $cartAddress, Method $method): Order
	{
		$order = new Order();
		$order->fill($cart->toArray());
		$order->payment_method = $method->getMethodName();
		$order->payment_data = $method->getAdditionalData();
		$this->orderRepository->save($order);

		$order->increment_id = 'OR-' . sprintf('%08d', $order->id);
		$this->orderRepository->save($order);

		$address = new OrderAddress();
		$address->fill($cartAddress->toArray());
		$address->order_id = $order->id;
		$this->addressRepository->save($address);

		$cartItems = $this->cartRepository->getItems($cart);

		foreach ($cartItems as $item)
		{
			$orderItem = new OrderItem();
			$orderItem->fill($item->toArray());
			$orderItem->order_id = $order->id;

			$this->itemRepository->save($orderItem);
		}

		return $order;
	}
}
