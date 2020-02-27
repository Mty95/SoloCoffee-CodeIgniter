<?php
namespace App\Model\OrderItem;

/**
 * Class OrderItems
 * @package App\Model\OrderItems
 *
 * @property-read int $id;
 * @property int $order_id;
 * @property int $product_id;
 * @property int $qty;
 * @property float $price;
 * @property float $total_price;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class OrderItem extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'order_id' => null,
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
