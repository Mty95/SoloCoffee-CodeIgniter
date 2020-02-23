<?php
namespace App\Model\CartItem;

use App\Repositories;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class CartItemFacade
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
        $this->validator = Validator::take(CartItem::class);
    }

    /**
     * @param array $data
     * @return CartItem
     *
     * @throws ValidationException
     */
    public function create(array $data): CartItem
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
            throw ValidationException::notValid();
        }

        return new CartItem($data);
    }
}