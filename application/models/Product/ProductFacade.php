<?php
namespace App\Model\Product;

use App\Repositories;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class ProductFacade
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
        $this->validator = Validator::take(Product::class);
    }

    /**
     * @param array $data
     * @return Product
     *
     * @throws ValidationException
     */
    public function create(array $data): Product
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
			throw ValidationException::notValid($this->errors());
        }

        return new Product($data);
    }
}
