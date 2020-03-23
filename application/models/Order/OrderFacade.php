<?php
namespace App\Model\Order;

use App\Repositories;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class OrderFacade
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
        $this->validator = Validator::take(Order::class);
    }

    /**
     * @param array $data
     * @return Order
     *
     * @throws ValidationException
     */
    public function create(array $data): Order
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
			throw ValidationException::notValid($this->errors());
        }

        return new Order($data);
    }
}
