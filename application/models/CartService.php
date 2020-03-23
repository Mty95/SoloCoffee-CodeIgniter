<?php
namespace App\Model;

use App\Model\CartItem;
use App\Model\Category\Category;
use App\Model\Product;
use App\Model\User\User;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\SimpleValidator;
use NewFramework\Exceptions\EntityException;
use NewFramework\Exceptions\ValidationException;

class CartService
{
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
	 * @var CartItem\Repository
	 */
	private $cartItemRepository;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Cart\Cart
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

		// Build ItemModel array with Product inside
		/** @var CartItem\CartItem[] $items */
		$items = $this->cartRepository->getItems($this->currentCart);
		$productRepository = Product\Repository::take();
		foreach ($items as $item)
		{
			$product = $productRepository->find($item->product_id);
			$this->items[$product->id] = new ItemModel($item, $product);
		}
	}

	public function errors(): array
	{
		return $this->validator->errors();
	}

	public function getDetails(): array
	{
		$items = [];
		$this->currentCart->total = 0;
		$this->currentCart->subtotal = 0;
		$this->currentCart->total_items = 0;

		foreach ($this->items as $item)
		{
			$items[] = $item->getDetails();

			$this->currentCart->subtotal += $item->getData()->total_price;
			$this->currentCart->total = $this->currentCart->subtotal;
			$this->currentCart->total_items += $item->getData()->qty;
		}

		try {
			$this->cartRepository->save($this->currentCart);
		} catch (DataException | EntityException $e) {
		}

		return array_merge($this->currentCart->toExport(), ['items' => $items]);
	}

	public function getItemsFromCategory(Category $category): array
	{
		$response = [];
		$products = $this->productRepository->getByCategory($category);

		foreach ($products as $product)
		{
			if ($this->productIsInCart($product))
			{
				$response[] = $this->items[$product->id]->getDetails();
				continue;
			}

			$response[] = (new ItemModel(new CartItem\CartItem(), $product))->getDetails();
		}

		return $response;
	}

	public function getItemDetails(Product\Product $product): array
	{
		return $this->getItemFromProduct($product)->getDetails();
	}

	private function getItemFromProduct(Product\Product $product)
	{
		if (isset($this->items[$product->id]))
		{
			return $this->items[$product->id];
		}

		return new ItemModel(
			new CartItem\CartItem(),
			$product
		);
	}

	/**
	 * @param array $data
	 * @return Product\Product
	 * @throws ValidationException
	 */
	public function addItem(array $data = []): Product\Product
	{
		$isValid = $this->validator->validate([
			'product' => 'trim|required|exists[products.slug]',
			'quantity' => 'trim|required|numeric|greater_than[0]'
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid($this->errors());
		}

		$product = $this->productRepository->findBySlug($data['product']);
		$item = $this->currentCart->addItem($this->cartItemRepository, $product, (int) $data['quantity']);
		$this->items[$product->id] = new ItemModel($item, $product);

		return $product;
	}

	/**
	 * @param array $data
	 * @return Product\Product
	 * @throws ValidationException
	 * @throws \Mty95\NewFramework\Exceptions\DataException
	 * @throws \NewFramework\Exceptions\EntityException
	 */
	public function updateItem(array $data = []): Product\Product
	{
		$isValid = $this->validator->validate([
			'product' => 'trim|required|exists[products.slug]',
			'quantity' => 'trim|required|numeric'
		], $data);

		if (!$isValid)
		{
			throw ValidationException::notValid($this->errors());
		}

		$product = $this->productRepository->findBySlug($data['product']);
		$item = $this->currentCart->updateItem($this->cartItemRepository, $product, (int) $data['quantity']);
		$this->items[$product->id] = new ItemModel($item, $product);

		if ($item->qty === 0)
		{
			$this->cartItemRepository->delete($item);
			unset($this->items[$product->id]);
		}

		return $product;
	}

	/**
	 * @param Product\Product $product
	 * @return array
	 * @throws \Exception
	 */
	public function removeItem(Product\Product $product): array
	{
		$this->currentCart->removeItem($this->cartItemRepository, $product);
		unset($this->items[$product->id]);

		return $this->getDetails();
	}

	private function productIsInCart(Product\Product $product)
	{
		return isset($this->items[$product->id]);
	}
}
