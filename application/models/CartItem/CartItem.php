<?php
namespace App\Model\CartItem;

/**
 * Class CartItem
 * @package App\Model\CartItem
 *
 * @property-read int $id;
 * @property int $cart_id;
 * @property int $product_id;
 * @property int $qty;
 * @property float $price;
 * @property float $total_price;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
 *
 * @property-read string $product_slug
 * @property-read string $product_name
 * @property-read string $product_image
*/
class CartItem extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'cart_id' => null,
		'product_id' => null,
		'qty' => null,
		'price' => null,
		'total_price' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'slug' => $this->product_slug,
			'name' => $this->product_name,
			'image' => product_image($this->product_image),
			'qty' => $this->qty,
			'price' => $this->price,
			'total_price' => $this->total_price,
		];
    }
}
