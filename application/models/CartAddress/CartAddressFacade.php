<?php
namespace App\Model\CartAddress;

use App\Repositories;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class CartAddressFacade
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
        $this->validator = Validator::take(CartAddress::class);
    }

    /**
     * @param array $data
     * @return CartAddress
     *
     * @throws ValidationException
     */
    public function create(array $data): CartAddress
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
			throw ValidationException::notValid($this->errors());
        }

        return new CartAddress($data);
    }
}
