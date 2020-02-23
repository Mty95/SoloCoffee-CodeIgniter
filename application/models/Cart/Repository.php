<?php
namespace App\Model\Cart;

use App\Model\CartItem\CartItem;
use App\Model\User\User;
use NewFramework\ActiveRecordConfig;

/**
 * Class Repository
 * @package App\Model\Cart
 *
 * @method Cart create(array $data = [])
 * @method Cart clone(Cart $entity)
 * @method int save(Cart $entity)
 * @method Cart findOrFail($id, string $entity = 'Cart')
 * @method Cart find($id)
 * @method Cart get()
 * @method Cart[] findAll(int $limit = 0, int $offset = 0)
*/
class Repository extends \NewFramework\Repository
{
	/**
	 * @var \App\Model\CartItem\Repository
	 */
	private $itemRepository;

	public function __construct(
		$db,
		ActiveRecordConfig $activeRecordConfig,
		\App\Model\CartItem\Repository $itemRepository
	)
	{
		parent::__construct($db, $activeRecordConfig);

		$this->itemRepository = $itemRepository;
	}

	public function getFromUser(User $user): ?Cart
	{
		return $this->where('user_id', $user->id)->get();
	}

	public function getFromUserOrCreateNew(User $user): Cart
    {
		$cart = $this->where('user_id', $user->id)->get();

		if (null === $cart)
		{
			$cart = new Cart();
			$cart->user_id = $user->id;
			$this->save($cart);
		}

		return $cart;
    }

	/**
	 * @param Cart $cart
	 * @return CartItem[]
	 */
    public function getItems(Cart $cart): array
	{
		return $this->itemRepository->where('cart_id', $cart->id)->findAll();
	}
}
