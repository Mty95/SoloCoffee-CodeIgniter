<?php
namespace App\Model\CartItem;

use App\Model\Cart\Cart;
use App\Model\Product\Product;

/**
 * Class Repository
 * @package App\Model\CartItem
 *
 * @method CartItem create(array $data = [])
 * @method CartItem clone(CartItem $entity)
 * @method int save(CartItem $entity)
 * @method CartItem findOrFail($id, string $entity = 'CartItem')
 * @method CartItem find($id)
 * @method CartItem get()
 * @method CartItem[] findAll(int $limit = 0, int $offset = 0)
*/
class Repository extends \NewFramework\Repository
{
    public function getFromCartAndProduct(Cart $cart, Product $product): CartItem
    {
		$item = $this->where('cart_id', $cart->id)
			->where('product_id', $product->id)->get();

		if (null === $item)
		{
			$item = new CartItem();
			$item->cart_id = $cart->id;
			$item->product_id = $product->id;
			$this->save($item);
		}

		return $item;
    }
}
