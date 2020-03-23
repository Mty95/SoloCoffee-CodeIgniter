<?php

use App\Library\Collection;
use App\Model\CustomerAddress\CustomerAddress;
use App\Model\CustomerAddress\CustomerAddressFacade;
use Core\API\Authenticated;

/**
 * Class Address
 */
class Address extends Authenticated
{
    protected $repository;
    protected $facade;

	protected $auth;
	protected $userData;
	protected $isAuthenticated = false;

	public function __construct()
	{
		parent::__construct();
		$this->assertUserIsAuthenticated();

		$this->repository = \App\Model\CustomerAddress\Repository::take();
		$this->facade = new CustomerAddressFacade();
	}

	protected function getCustomerAddresses()
	{
		return $this->repository->where('user_id', $this->user->id)->findAll();
	}

	/**
	 * @Rest(method="GET", route="/")
	 */
	public function getAddressList(): bool
	{
		return $this->success([
			'addresses' => Collection::toExport($this->getCustomerAddresses()),
		]);
	}

	/**
	 * @Rest(method="PUT", route="/")
	 */
	public function createAddress(): bool
	{
		try {
			$data = (array)$this->put();
			$data['user_id'] = $this->user->id;
			$address = $this->facade->create($data);
		} catch (Throwable $exception) {
			return $this->failException($exception);
		}

		return $this->success([
			'message' => 'Dirección agregada.',
			'address' => $address->toExport(),
			'addresses' => Collection::toExport($this->getCustomerAddresses()),
		]);
	}

	/**
	 * @Rest(method="PATCH", route="/{id}")
	 * @param CustomerAddress $address
	 * @return bool
	 */
	public function updateAddress(CustomerAddress $address): bool
	{
		try {
			$data = (array)$this->patch();
			$data['user_id'] = $this->user->id;

			$this->facade->guardUserIdAllowedToEditOrDelete($this->user, $address);
			$this->facade->update($address, $data);
		} catch (Throwable $e) {
			return $this->failException($e);
		}

		return $this->success([
			'message' => 'Dirección actualizada.',
			'address' => $address->toExport(),
			'addresses' => Collection::toExport($this->getCustomerAddresses()),
		]);
	}

	/**
	 * @Rest(method="DELETE", route="/{id}")
	 * @param CustomerAddress $address
	 * @return bool
	 */
	public function deleteAddress(CustomerAddress $address): bool
	{
		try {
			$this->facade->guardUserIdAllowedToEditOrDelete($this->user, $address);
			$this->facade->delete($address);
		} catch (Throwable $e) {
			return $this->failException($e);
		}

		return $this->success([
			'message' => 'Dirección actualizada.',
			'address' => $address->toExport(),
			'addresses' => Collection::toExport($this->getCustomerAddresses()),
		]);
	}
}
