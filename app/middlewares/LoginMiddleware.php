<?php

declare(strict_types=1);

namespace app\middlewares;

use flight\Engine;

class LoginMiddleware {
	
	/** @var Engine */
	protected Engine $app;
	
	public function __construct(Engine $app)
	{
		$this->app = $app;
	}

	public function before(): void
	{	
		if ($this->app->session()->exist('user') === false) {
			$this->app->redirect($this->app->getUrl('login'));
		}
	}
}