<?php
namespace Core\Controller;

use Mty95\AdminDashboard\ThemeController;

class AdminController extends ThemeController
{
	protected function render(string $page, array $data = [])
	{
		// $data['user'] = Repository::take()->find(1);

		return parent::render($page, $data);
	}
}