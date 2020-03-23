<?php
namespace App\Model\CustomerAddress;

use App\Model\User\User;
use App\Repositories;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Exceptions\EntityException;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class CustomerAddressFacade
{
    use FacadeValidatorTrait;

    /**
     * @var Repository
     */
    private $repository;

    /**
     * @var Validator
     */
    private $validator;

    public function __construct()
    {
        $this->repository = Repository::take();
        $this->validator = Validator::take(CustomerAddress::class);
    }

	/**
	 * @param User $user
	 * @param CustomerAddress $address
	 * @throws EntityException
	 */
    public function guardUserIdAllowedToEditOrDelete(User $user, CustomerAddress $address)
	{
		if (!($user->id === $address->user_id))
		{
			throw new EntityException('User can not be edit or delete this address.');
		}
    }

	/**
	 * @param array $data
	 * @return CustomerAddress
	 *
	 * @throws ValidationException
	 * @throws DataException
	 */
    public function create(array $data): CustomerAddress
    {
    	$this->validateOrThrows($data, ['create']);

		$address = new CustomerAddress($data);
		$this->repository->save($address);

        return $address;
    }

	/**
	 * @param CustomerAddress $address
	 * @param array $data
	 * @return CustomerAddress
	 *
	 * @throws ValidationException
	 * @throws DataException
	 */
    public function update(CustomerAddress $address, array $data): CustomerAddress
	{
		$this->validateOrThrows($data, ['update']);

		$address->fill((array) $data);
		$this->repository->save($address);

		return $address;
    }

    public function delete(CustomerAddress $address)
	{
		return $this->repository->delete($address);
	}
}
