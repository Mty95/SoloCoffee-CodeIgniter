<?php
namespace App\Model;

use App\Library\Collection;
use App\Model\Product;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\SimpleValidator;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

class Cart
{
	use FacadeValidatorTrait;

	/**
	 * @var Repository
	 */
	private $repository;

	/**
	 * @var SimpleValidator
	 */
	private $validator;
	/**
	 * @var Product\Repository
	 */
	private $productRepository;
	/**
	 * @var Cart\Repository
	 */
	private $cartRepository;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var CartItem\Repository
	 */
	private $cartItemRepository;

	public function __construct(
		SimpleValidator $validator,
		Product\Repository $productRepository,
		Cart\Repository $cartRepository,
		CartItem\Repository $cartItemRepository,
		User $user
	)
	{
		$this->validator = $validator;
		$this->productRepository = $productRepository;
		$this->cartRepository = $cartRepository;
		$this->cartItemRepository = $cartItemRepository;
		$this->user = $user;
	}

	/**
	 * @param array $data
	 * @return array
	 *
	 * @throws ValidationException
	 * @throws DataException
	 * @throws EntityException
	 */
	public function addItem(array $data = []): array
	{
		$isValid = $this->validator->validate([
			'product' => 'trim|required|exists[products.slug]',
			'quantity' => 'trim|required|numeric|greater_than[0]'
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid();
		}

		$product = $this->productRepository->where('slug', $data['product'])->get();
		$cart = $this->cartRepository->getFromUserOrCreateNew($this->user);
		$itemAdded = $cart->addItem($this->cartItemRepository, $product, (int) $data['quantity']);

		return $this->showCartResponse($cart);
	}

	/**
	 * @param array $data
	 * @return array
	 * @throws DataException
	 * @throws EntityException
	 * @throws ValidationException
	 */
	public function updateItem(array $data = []): array
	{
		$isValid = $this->validator->validate([
			'product' => 'trim|required|exists[products.slug]',
			'quantity' => 'trim|required|numeric'
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid();
		}

		$product = $this->productRepository->where('slug', $data['product'])->get();
		$cart = $this->cartRepository->getFromUserOrCreateNew($this->user);
		$itemAdded = $cart->updateItem($this->cartItemRepository, $product, (int) $data['quantity']);

		if ($itemAdded->qty === 0)
		{
			$this->cartItemRepository->delete($itemAdded);
		}

		return $this->showCartResponse($cart);
	}

	/**
	 * @param Product\Product $product
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function removeItem(Product\Product $product): array
	{
		$cart = $this->cartRepository->getFromUserOrCreateNew($this->user);
		$cart->removeItem($this->cartItemRepository, $product);

		return $this->showCartResponse($cart);
	}

	public function getDetails(): array
	{
		$cart = $this->cartRepository->getFromUserOrCreateNew($this->user);

		return $this->showCartResponse($cart);
	}

	private function showCartResponse(\App\Model\Cart\Cart $cart): array
	{
		$items = $this->cartRepository->getItems($cart);
		$cart->total = 0;
		$cart->subtotal = 0;
		$cart->total_items = 0;

		foreach ($items as $item)
		{
			$cart->subtotal += $item->total_price;
			$cart->total = $cart->subtotal;
			$cart->total_items += $item->qty;
		}

		$this->cartRepository->save($cart);

		return array_merge($cart->toExport(), ['items' => Collection::toExport($items)]);
	}
}
