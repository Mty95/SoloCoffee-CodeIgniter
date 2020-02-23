<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends \Mty95\AdminDashboard\ThemeController
{
    protected $module = 'welcome';
    protected $resourceUrl = 'welcome';

    public function index(): void
    {
        $this->render('message', ['title' => 'Welcome to CodeIgniter']);

		$cart = \App\Services::take(\App\Model\Cart::class, [new \App\Model\User\User()]);
		// var_dump($cart);
    }

    public function ci4(): void
    {
        $this->render('ci4');
    }

    public function validation(): void
    {
        helper('form');
        $this->load->library('form_validation');

		$postData = $this->input->post();

        if ($postData)
		{
			$validation = Services::validation();
			$isValidate = $validation->validate([
				'terms' => 'accepted',
				'field' => 'required|date_format[Y-m-d]',
				'first_name' => 'trim',
				'last_name' => 'trim',
				'password' => 'trim',
				'password_confirm' => 'trim|required|matches[password]',
				'image' => 'image|max_size[1024]|max_dims[2000,1200]',
			], $postData);

			if ($isValidate)
			{
				$upload = Services::upload('./public/upload', '*');
				$upload->setFileName($postData['first_name']);
				$upload->canOverwrite(true);

				if (!$upload->doUpload('image'))
				{
					echo_data('No se pudo subir el archivo.');
					echo_data($upload->errors());
				} else {
					echo_data('Archivo subido con éxito.');
					echo_data($upload->infoData());
				}
			}

			$data = ['title' => 'Validation'];
			$this->render('validation', $data);
			return;
		}

        $data = ['title' => 'Validation'];
        $this->render('validation', $data);
    }
}
