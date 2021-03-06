<?php
namespace App\Model;

use App\Library\Collection;
use App\Model\CartItem;
use App\Model\Product;
use App\Model\User\Repository;
use App\Model\User\User;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\SimpleValidator;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

/**
 * Class CartOld
 * @package App\Model
 *
 * @deprecated
 */
class CartOld
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

	/**
	 * @var \App\Model\Cart\Cart
	 */
	private $currentCart;

	/**
	 * @var ItemModel[]
	 */
	private $items = [];

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

		$this->currentCart = $this->cartRepository->getFromUserOrCreateNew($this->user);

		// Build Items with Product inside
		/** @var CartItem\CartItem[] $items */
		$items = $this->cartRepository->getItems($this->currentCart);
		$productRepository = Product\Repository::take();
		foreach ($items as $item)
		{
			$product = $productRepository->find($item->product_id);
			$this->items[$product->id] = new ItemModel($item, $product);
		}
	}

	/**
	 * @return ItemModel[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	public function getItemDetails(Product\Product $product): array
	{
		$details = [
			'product' => $product->toExport(),
			'in_cart' => false,
			'amount' => 0,
		];

		/** @var CartItem\CartItem $item */
		foreach ($this->cartRepository->getItems($this->currentCart) as $item)
		{
			if ($item->product_id === $product->id)
			{
				$details['in_cart'] = true;
				$details['amount'] = $item->qty;
			}
		}

		return $details;
	}

	public function getDetails(): array
	{
		return $this->showCartResponse($this->currentCart);
	}



	// -------------------------------------------------------------------------
	// Refactoring this

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
			throw ValidationException::notValid($this->errors());
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
			throw ValidationException::notValid($this->errors());
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

	/**
	 * @param Product\Product[] $products
	 * @return array
	 */
	public function getProductsDetails(array $products): array
	{
		$cart = $this;
		return array_map(static function (Product\Product $product) use ($cart) {
			return $cart->getProductDetails($product);
		}, $products);
	}
}
