<?php
namespace App\Model\Product;

/**
 * Class Product
 * @package App\Model\Product
 *
 * @property-read int $id;
 * @property int $category_id;
 * @property $name;
 * @property $slug;
 * @property $image;
 * @property $long_description;
 * @property $small_description;
 * @property float $price;
 * @property float $special_price;
 * @property int $stock;
 * @property bool $active;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
 *
 * @property-read string $category_name;
*/
class Product extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'category_id' => null,
		'name' => null,
		'slug' => null,
		'image' => null,
		'long_description' => null,
		'small_description' => null,
		'price' => null,
		'special_price' => null,
		'stock' => null,
		'active' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'category_id' => $this->category_id,
			'category_name' => $this->category_name,
			'name' => $this->name,
			'slug' => $this->slug,
			'image' => product_image($this->image),
			'long_description' => $this->long_description,
			'small_description' => $this->small_description,
			'price' => $this->price,
			'special_price' => $this->special_price,
			'stock' => $this->stock,
			'active' => $this->active,
		];
    }
}
