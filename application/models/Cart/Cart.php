<?php
namespace App\Model\Cart;

use App\Model\CartItem\CartItem;
use App\Model\CartItem\Repository as CartItemRepository;
use App\Model\Product\Product;

/**
 * Class Cart
 * @package App\Model\Cart
 *
 * @property-read int $id;
 * @property int $user_id;
 * @property int $address_id;
 * @property float $subtotal;
 * @property float $discount;
 * @property float $shipping;
 * @property float $total;
 * @property int $total_items;
 * @property $coupons;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class Cart extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'user_id' => null,
		'address_id' => null,
		'subtotal' => null,
		'discount' => null,
		'shipping' => null,
		'total' => null,
		'total_items' => null,
		'coupons' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'address_id' => $this->address_id,
			'subtotal' => $this->subtotal,
			'discount' => $this->discount,
			'shipping' => $this->shipping,
			'total' => $this->total,
			'total_items' => (int) $this->total_items,
		];
	}

	public function addItem(CartItemRepository $itemRepository, Product $product, int $quantity): CartItem
	{
		$item = $itemRepository->getFromCartAndProduct($this, $product);
		$item->qty += $quantity;
		$item->price = $product->price;
		$item->total_price = $item->qty * $item->price;
		$itemRepository->save($item);

		return $item;
	}

    public function updateItem(CartItemRepository $itemRepository, Product $product, int $quantity): CartItem
    {
		$item = $itemRepository->getFromCartAndProduct($this, $product);
		$item->qty = $quantity;
		$item->price = $product->price;
		$item->total_price = $item->qty * $item->price;
		$itemRepository->save($item);

		return $item;
    }

	/**
	 * @param CartItemRepository $itemRepository
	 * @param Product $product
	 * @throws \Exception
	 */
    public function removeItem(CartItemRepository $itemRepository, Product $product): void
	{
		$item = $itemRepository->where('cart_id', $this->id)
			->where('product_id', $product->id)->get();

		if (null === $item)
		{
			throw new \Exception('This product does not exist in your quote');
		}

		$itemRepository->delete($item);
	}
}
