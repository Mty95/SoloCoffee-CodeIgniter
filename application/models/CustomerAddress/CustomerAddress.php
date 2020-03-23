<?php
namespace App\Model\CustomerAddress;

/**
 * Class CustomerAddress
 * @package App\Model\CustomerAddress
 *
 * @property-read int $id;
 * @property int $user_id;
 * @property $name;
 * @property $dni;
 * @property $cellphone;
 * @property $line1;
 * @property $line2;
 * @property $map_position;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class CustomerAddress extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'user_id' => null,
		'name' => null,
		'dni' => null,
		'cellphone' => null,
		'line1' => null,
		'line2' => null,
		'map_position' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

    public function toExport()
    {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'dni' => $this->dni,
			'cellphone' => $this->cellphone,
			'line1' => $this->line1,
			'line2' => $this->line2,
			'map_position' => $this->map_position,
		];
    }
}
