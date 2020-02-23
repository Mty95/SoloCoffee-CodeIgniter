<?php

/**
 * Class Builder
 *
 * Don't touch this class.
 */
class Builder extends \Core\Controller\AdminController
{
	use \App\Library\Mty95\SimpleApiRestTrait;

	protected $module = 'administrative\builder';
	protected $baseFile = '../index.php';
	protected $resourceUrl = 'administrative\builder';

	public function index(): void
	{

	}

	public function form($table = ''): void
	{
		$data = [
			'tables' => $this->db->list_tables(),
		];

		if ($table !== '')
		{
			$data['fields'] = $this->db->field_data($table);
			$data['config'] = \NewFramework\ActiveRecordMapper::fromTableName($table);
		}

		$this->render('form', $data);
	}

	public function form_post(): void
	{
		$model = new \Mty95\Generation\Model\FormBuilder($this->input->post('tableName'));
		$data = $this->input->post('crud');

		$model->run($data);

		$this->success([
			'message' => 'OK',
			'data' => json_encode($data, true),
		]);
	}
}
