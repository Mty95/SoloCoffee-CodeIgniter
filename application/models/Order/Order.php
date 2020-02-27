<?php
namespace App\Model\Order;

/**
 * Class Order
 * @package App\Model\Order
 *
 * @property-read int $id;
 * @property $increment_id;
 * @property int $user_id;
 * @property $status;
 * @property $payment_method;
 * @property float $subtotal;
 * @property float $discount;
 * @property float $shipping;
 * @property float $total;
 * @property int $total_items;
 * @property $coupons;
 * @property $payment_data;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class Order extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'increment_id' => null,
		'user_id' => null,
		'status' => null,
		'payment_method' => null,
		'subtotal' => null,
		'discount' => null,
		'shipping' => null,
		'total' => null,
		'total_items' => null,
		'coupons' => null,
		'payment_data' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

    public function toExport(): array
    {
    	return [
    		'increment_id' => $this->increment_id,
    		'status' => $this->status,
    		'payment_method' => $this->payment_method,
    		'subtotal' => $this->subtotal,
    		'discount' => $this->discount,
    		'shipping' => $this->shipping,
    		'total' => $this->total,
    		'total_items' => $this->total_items,
    		'date' => $this->created_at->format('Y-m-d H:i:s'),
		];
    }
}
