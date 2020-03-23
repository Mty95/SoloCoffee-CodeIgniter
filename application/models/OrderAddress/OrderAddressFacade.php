<?php
namespace App\Model\OrderAddress;

use App\Repositories;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class OrderAddressFacade
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
        $this->validator = Validator::take(OrderAddress::class);
    }

    /**
     * @param array $data
     * @return OrderAddress
     *
     * @throws ValidationException
     */
    public function create(array $data): OrderAddress
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
			throw ValidationException::notValid($this->errors());
        }

        return new OrderAddress($data);
    }
}
