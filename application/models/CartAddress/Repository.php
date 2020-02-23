<?php
namespace App\Model\CartAddress;

use App\Model\Cart\Cart;
use App\Model\User\User;

/**
 * Class Repository
 * @package App\Model\CartAddress
 *
 * @method CartAddress create(array $data = [])
 * @method CartAddress clone(CartAddress $entity)
 * @method int save(CartAddress $entity)
 * @method CartAddress findOrFail($id, string $entity = 'CartAddress')
 * @method CartAddress find($id)
 * @method CartAddress get()
 * @method CartAddress[] findAll(int $limit = 0, int $offset = 0)
*/
class Repository extends \NewFramework\Repository
{
	public function getByUser(User $user): ?CartAddress
	{
		return null;
    }

	public function getByCart(Cart $cart): ?CartAddress
	{
		return $this->where('cart_id', $cart->id)->get();
    }
}
