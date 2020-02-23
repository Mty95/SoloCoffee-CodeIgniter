<?php
namespace App\Model\Category;

/**
 * Class Category
 * @package App\Model\Category
 *
 * @property-read int $id;
 * @property $name;
 * @property $slug;
 * @property $image;
 * @property $long_description;
 * @property $small_description;
 * @property bool $active;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class Category extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'name' => null,
		'slug' => null,
		'image' => null,
		'long_description' => null,
		'small_description' => null,
		'active' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'slug' => $this->slug,
			'name' => $this->name,
			'image' => upload_url($this->image),
			'long_description' => $this->long_description,
			'small_description' => $this->small_description,
			'active' => $this->active,
		];
    }
}
