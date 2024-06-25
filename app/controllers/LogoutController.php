<?php

declare(strict_types=1);

namespace app\controllers;

class LogoutController extends BaseController
{
	/**
	 * Index
	 * 
	 * @return void
	 */
	public function index(): void
	{
		$this->session()->destroy();
		$this->redirect($this->getUrl('blog'));
	}
}