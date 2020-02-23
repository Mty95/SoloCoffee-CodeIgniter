<?php
namespace App\Model\User;

use App\Exceptions\ValidationFieldException;
use App\Repositories;
use Mty95\NewFramework\Exceptions\DataException;
use Mty95\NewFramework\Validation\FacadeValidatorTrait;
use NewFramework\Exceptions\EntityException;
use NewFramework\Validator;
use NewFramework\Exceptions\ValidationException;

class UserFacade
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
        $this->validator = Validator::take(User::class);
    }

    /**
     * @param array $data
     * @return User
     *
     * @throws ValidationException
     */
    public function create(array $data): User
    {
        $isValidate = $this->validator->validate($data, ['create', 'onTest']);

        if (!$isValidate)
        {
            throw ValidationException::notValid();
        }

        return new User($data);
    }

	/**
	 * @param array $data
	 * @return User
	 * @throws ValidationException
	 * @throws ValidationFieldException
	 */
    public function login(array $data = []): User
	{
    	$isValidate = $this->validator->validate($data, ['login']);

    	if (!$isValidate)
		{
			throw ValidationException::notValid();
		}

    	$user = $this->repository->where('username', $data['username'])->get();

    	if (!password_verify($data['password'], $user->password))
		{
			throw ValidationFieldException::notValid('Incorrect Password', 'password');
		}

    	return $user;
    }

	/**
	 * @param array $data
	 * @return User
	 * @throws ValidationException
	 * @throws DataException
	 * @throws EntityException
	 */
    public function register(array $data): User
	{
		$isValidate = $this->validator->validate($data, ['register']);

		if (!$isValidate)
		{
			throw ValidationException::notValid();
		}

		$user = new User($data);
		$user->setPassword($data['password']);
		$this->repository->save($user);

		return $user;
	}

	/**
	 * @param User $user
	 * @param array $data
	 * @return User
	 * @throws DataException
	 * @throws EntityException
	 * @throws ValidationException
	 */
	public function updateProfile(User $user, array $data): User
    {
    	if (isset($data['password']) && password_verify($data['password'], $user->password))
		{
			unset($data['password']);
		}

		if (isset($data['email']) && $user->email === $data['email'])
		{
			unset($data['email']);
		}

		$isValidate = $this->validator->validate($data, ['update-profile']);

		if (!$isValidate)
		{
			throw ValidationException::notValid();
		}

		$user->fill($data);
		$this->repository->save($user);

		return $user;
    }
}
