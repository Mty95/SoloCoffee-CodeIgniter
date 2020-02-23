<?php

namespace App\Model;

use App\Model\Cart\Repository as CartRepository;
use App\Model\CartAddress\CartAddress;
use App\Model\CartAddress\Repository as AddressRepository;
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

	public function __construct(
		SimpleValidator $validator,
		CartRepository $cartRepository,
		AddressRepository $addressRepository,
		User $user
	)
	{
		$this->validator = $validator;
		$this->addressRepository = $addressRepository;
		$this->user = $user;
		$this->cartRepository = $cartRepository;
	}

	public function getAddressInfo(): array
	{
		$cart = $this->cartRepository->getFromUser($this->user);

		if (null === $cart)
		{
			throw new Exception('This user does not have a active cart.');
		}

		if ($cart->total_items === 0)
		{
			throw new Exception('Please add some products before to checkout.');
		}

		$address = $this->addressRepository->getByCart($cart);

		if (null === $address)
		{
			throw new Exception('You need to set your address information before to pay your order.');
		}

		return $address->toExport();
	}

	/**
	 * @param array $data
	 * @return array
	 * @throws ValidationException
	 * @throws DataException
	 * @throws EntityException
	 */
	public function setAddressInfo(array $data = []): array
	{
		if (empty($data))
		{
			throw ValidationException::notValid();
		}

		$isValid = $this->validator->validate([
			'name' => 'trim|required',
			'dni' => 'trim|required|min_length[8]|max_length[11]',
			'cellphone' => 'trim|required|min_length[9]',
			'line1' => 'trim|required',
			'line2' => 'trim|required',
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid();
		}


		// --- From here is business logic
		$cart = $this->cartRepository->getFromUser($this->user);

		if (null === $cart)
		{
			throw new Exception('This user does not have a active cart.');
		}

		$address = $this->addressRepository->getByCart($cart);

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

		return [
			'methods' => (new PaymentMethod())->getMethods(),
			'cart_info' => $cart->toExport(),
			'address_info' => $address->toExport(),
		];
	}

	public function getPaymentMethods(): array
	{
		$cart = $this->cartRepository->getFromUser($this->user);

		if (null === $cart)
		{
			throw new Exception('This user does not have a active cart.');
		}

		$address = $this->addressRepository->getByCart($cart);

		if (null === $address)
		{
			throw new Exception('You need to set your address information before to pay your order.');
		}

		return [
			'methods' => (new PaymentMethod())->getMethods(),
			'cart_info' => $cart->toExport(),
			'address_info' => $address->toExport(),
		];
	}
}
