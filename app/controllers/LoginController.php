<?php

declare(strict_types=1);

namespace app\controllers;

class LoginController extends BaseController
{
	/**
	 * Index
	 * 
	 * @return void
	 */
	public function index(): void
	{
		$this->render('login/index.latte', [ 'page_title' => 'Login' ]);
	}

	/**
	 * Authenticate
	 * 
	 * @return void
	 */
	public function authenticate(): void
	{
		$postData = $this->request()->data;

		// When you actually do authentication, please use a secure method
		// hashing method like password_hash() and password_verify()
		// also, please don't encrypt passwords. Encrypting is not the same as hashing.
		if($postData->password === 'password') {
			$this->session()->set('user', $postData->username);

			// Sets the current user role
			if($postData->username === 'admin') {
				$this->session()->set('role', 'admin');
			} else if($postData->username === 'editor') {
				$this->session()->set('role', 'editor');
			} else {
				$this->session()->set('role', 'user');
			}
			$this->session()->commit();
			$this->redirect($this->getUrl('blog'));
			exit;
		}
		$this->redirect($this->getUrl('login'));
	}
}