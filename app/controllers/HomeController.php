<?php

declare(strict_types=1);

namespace app\controllers;

use flight\Engine;

class HomeController
{
    /** @var Engine */
    protected Engine $app;

    /**
     * Constructor
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
    }

	/**
	 * Index
	 * 
	 * @return void
	 */
	public function index(): void
	{
		$this->app->render('home.latte', [ 'page_title' => 'Home' ]);
	}
}
