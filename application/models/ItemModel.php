<?php
namespace App\Model;

use App\Model\CartItem\CartItem;
use App\Model\Product\Product;

class ItemModel
{
	/**
	 * @var Product
	 */
	protected $product;
	/**
	 * @var CartItem
	 */
	private $item;

	public function __construct(CartItem $item, Product $product)
	{
		$this->item = $item;
		$this->product = $product;
	}

	public function getData(): CartItem
	{
		return $this->item;
	}

	public function getProduct(): Product
	{
		return $this->product;
	}

	public function getDetails(): array
	{
		$productData = $this->product->toExport();

		if ($this->item->id === 0)
		{
			return [
				'product' => $productData,
				'in_cart' => false,
				'amount' => 0,
			];
		}

		return [
			'product' => $productData,
			'in_cart' => true,
			'amount' => $this->item->qty,
		];
	}
}
