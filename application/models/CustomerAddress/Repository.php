<?php
namespace App\Model\CustomerAddress;

use App\Model\User\User;

/**
 * Class Repository
 * @package App\Model\CustomerAddress
 *
 * @method CustomerAddress create(array $data = [])
 * @method CustomerAddress clone(CustomerAddress $entity)
 * @method int save(CustomerAddress $entity)
 * @method CustomerAddress findOrFail($id, string $entity = 'CustomerAddress')
 * @method CustomerAddress find($id)
 * @method CustomerAddress get()
 * @method CustomerAddress[] findAll(int $limit = 0, int $offset = 0)
*/
class Repository extends \NewFramework\Repository
{
	public function ofUser(User $user)
	{
		return $this->where('user_id', $user->id);
    }
}
