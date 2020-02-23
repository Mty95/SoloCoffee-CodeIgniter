<?php
namespace App\Model\CartAddress;

/**
 * Class CartAddress
 * @package App\Model\CartAddress
 *
 * @property-read int $id;
 * @property int $cart_id;
 * @property $name;
 * @property $dni;
 * @property $cellphone;
 * @property $line1;
 * @property $line2;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class CartAddress extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'cart_id' => null,
		'name' => null,
		'dni' => null,
		'cellphone' => null,
		'line1' => null,
		'line2' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'name' => $this->name,
			'dni' => $this->dni,
			'cellphone' => $this->cellphone,
			'line1' => $this->line1,
			'line2' => $this->line2,
		];
    }
}
