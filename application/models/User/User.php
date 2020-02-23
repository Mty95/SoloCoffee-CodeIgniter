<?php
namespace App\Model\User;

/**
 * Class User
 * @package App\Model\User
 *
 * @property-read int $id;
 * @property $username;
 * @property $password;
 * @property $email;
 * @property $first_name;
 * @property $last_name;
 * @property $dob;
 * @property $active;
 * @property-read \DateTime $created_at;
 * @property-read \DateTime $updated_at;
 * @property-read \DateTime $deleted_at;
*/
class User extends \NewFramework\Entity
{
    protected $attributes = [
        'id' => null,
		'username' => null,
		'password' => null,
		'email' => null,
		'first_name' => null,
		'last_name' => null,
		'dob' => null,
		'active' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
    ];
    
    protected $protected = ['id'];

	public function toExport(): array
	{
		return [
			'username' => $this->username,
			'email' => $this->email,
			'first_name' => $this->first_name,
			'last_name' => $this->last_name,
			'dob' => $this->dob,
		];
    }

	public function setPassword(string $password): void
	{
		$this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }
}
