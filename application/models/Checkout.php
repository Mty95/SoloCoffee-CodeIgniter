<?php

namespace App\Model;

use App\Model\Cart\Repository as CartRepository;
use App\Model\CartAddress\CartAddress;
use App\Model\CartAddress\Repository as AddressRepository;
use App\Model\CustomerAddress\CustomerAddress;
use App\Model\Order\Order;
use App\Model\Order\Repository;
use App\Model\PaymentMethod\BankTransfer;
use App\Model\PaymentMethod\CashOnDelivery;
use App\Model\PaymentMethod\PagoEfectivo;
use App\Model\User\User;
use Exception;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\SimpleValidator;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

class Checkout
{
	use FacadeValidatorTrait;

	/**
	 * @var SimpleValidator
	 */
	private $validator;
	/**
	 * @var AddressRepository
	 */
	private $addressRepository;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var CartRepository
	 */
	private $cartRepository;
	/**
	 * @var OrderService
	 */
	private $orderService;

	public function __construct(
		SimpleValidator $validator,
		CartRepository $cartRepository,
		AddressRepository $addressRepository,
		OrderService $orderService,
		User $user
	)
	{
		$this->validator = $validator;
		$this->addressRepository = $addressRepository;
		$this->user = $user;
		$this->cartRepository = $cartRepository;
		$this->orderService = $orderService;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAddressInfo(): array
	{
		$cart = $this->getActiveCartFromUser();

		if ($cart->total_items === 0)
		{
			throw new Exception('Please add some products before to checkout.');
		}

		$address = $this->getValidAddressFromCart($cart);

		return $address->toExport();
	}

	public function getLastAddressInfo(): array
	{
		$address = $this->orderService->getLastAddressByUser($this->user);

		if (null === $address)
		{
			return [
				'name' => '',
				'dni' => '',
				'cellphone' => '',
				'line1' => '',
				'line2' => '',
			];
		}

		return $address->toExport();
	}

	/**
	 * @param array $data
	 * @return array
	 * @throws ValidationException
	 * @throws DataException
	 * @throws EntityException
	 * @throws \Exception
	 */
	public function setAddressInfo(array $data = []): array
	{
		if (empty($data))
		{
			throw ValidationException::notValid($this->errors());
		}

		$isValid = $this->validator->validate([
			'id' => 'trim|required|exists[customer_address.id]',
			/*'name' => 'trim|required',
			'dni' => 'trim|required|min_length[8]|max_length[11]',
			'cellphone' => 'trim|required|min_length[9]',
			'line1' => 'trim|required|min_length[10]',
			'line2' => 'trim|required|min_length[4]',*/
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid($this->errors());
		}


		// --- From here is business logic
		$cart = $this->getActiveCartFromUser();

		$address = \App\Model\CustomerAddress\Repository::take()->find($data['id']);
		/*$address = $this->addressRepository->getByCart($cart);

		if (null === $address)
		{
			$address = new CartAddress();
		}

		$address->fill($data);
		$address->cart_id = $cart->id;
		try {
			$this->addressRepository->save($address);
		} catch (EntityException $e) {
		}
		*/

		$cart->address_id = $address->id;
		try {
			$this->cartRepository->save($cart);
		} catch (EntityException | DataException $e) {
		}

		return [
			'methods' => (new PaymentMethod())->getMethods($cart),
			'cart_info' => $cart->toExport(),
			'address_info' => $address->toExport(),
		];
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getPaymentMethods(): array
	{
		$cart = $this->getActiveCartFromUser();
		// $address = $this->addressRepository->getByCart($cart);

		if ($cart->address_id === 0)
		{
			throw new Exception('You need to set your address information before to pay your order.');
		}

		return [
			'methods' => (new PaymentMethod())->getMethods($cart),
			'cart_info' => $cart->toExport(),
//			'address_info' => $address->toExport(),
		];
	}

	/**
	 * @param array $data
	 * @return array
	 * @throws Exception
	 */
	public function processPayment(array $data = []): array
	{
		$cart = $this->getActiveCartFromUser();
		// $address = $this->getValidAddressFromCart($cart);
		$address = \App\Model\CustomerAddress\Repository::take()->find($cart->address_id);

		// -------

		if (!isset($data['method']))
		{
			throw new Exception('You need to write a valid method name');
		}

		$method = null;

		if ($data['method'] === 'culqi')
		{
			$method = new \App\Model\PaymentMethod\Culqi();
		}

		if ($data['method'] === 'pago_efectivo')
		{
			$method = new PagoEfectivo();
		}

		if ($data['method'] === BankTransfer::METHOD_NAME)
		{
			$method = new BankTransfer();
		}

		if ($data['method'] === CashOnDelivery::METHOD_NAME)
		{
			$method = new CashOnDelivery();
		}

		if (null === $method)
		{
			throw new Exception('No method available.');
		}

		if ($method->isCanProcessing())
		{
			$method->execute($cart, $address, $this->user, $data['additional_data'] ?? []);
		}

		$order = $this->orderService->createOrder($cart, $address, $method);

		if ($method->isOffline())
		{
			$method->executeOffline($order, $address, $this->user, $data['additional_data'] ?? []);
			$this->orderService->updateOfflineOrder($order, $method);
		}

		$this->deleteCurrentCart($cart, $address);

		return [
			'message' => '¡Pago realizado con éxito!',
			'order_id' => $order->increment_id,
			'order' => $this->orderService->getDetails($order),
		];
	}

	private function deleteCurrentCart(\App\Model\Cart\Cart $cart, CustomerAddress $address): void
	{
		$items = $this->cartRepository->getItems($cart);
		$this->cartRepository->delete($cart);
		// $this->addressRepository->delete($address);
	}

	/**
	 * @return Cart\Cart
	 * @throws Exception
	 */
	private function getActiveCartFromUser(): Cart\Cart
	{
		return $this->cartRepository->getActiveFromUser($this->user);
	}

	/**
	 * @param Cart\Cart $cart
	 * @return CartAddress
	 * @throws Exception
	 */
	private function getValidAddressFromCart(Cart\Cart $cart): CartAddress
	{
		$address = $this->addressRepository->getByCart($cart);

		if (null === $address)
		{
			throw new Exception('You need to set your address information before to pay your order.');
		}

		return $address;
	}
}
