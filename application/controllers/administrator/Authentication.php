<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Core\Controller\AdminController;

class Authentication extends AdminController
{
	protected $module = 'admin\auth';
	protected $resourceUrl = 'admin\auth';

	public function index(): void
	{

	}
}
