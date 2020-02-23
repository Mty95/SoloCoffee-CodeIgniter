<?php
namespace App\Library\Mty95;

trait SimpleApiRestTrait
{
	protected function success(array $data, int $code = 200): void
	{
		header('Content-Type: application/json');
		echo json_encode($this->compressResponse(true, $data, $code));
	}

	protected function fail(array $data, int $code = 200): void
	{
		header('Content-Type: application/json');
		echo json_encode($this->compressResponse(false, $data, $code));
	}

	private function compressResponse(bool $status, $data, int $code = 200)
	{
		$response['status'] = $status;
		$response['code'] = $code;

		if (is_string($data))
		{
			$response['message'] = $data;
		}

		if (is_object($data))
		{
			$data = (array)$data;
		}

		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$response[$key] = $value;
			}
		}

		return $response;
	}
}
