<?php

declare(strict_types=1);

namespace app\controllers;

use flight\Engine;
use flight\net\Request;
use flight\net\Response;
use Ghostff\Session\Session;
use flight\database\PdoWrapper;
use flight\Permission;

/**
 * These help with IDE autocompletion and type hinting if you 
 * use the shortcut method.
 * 
 * @method Request request()
 * @method Response response()
 * @method Session session()
 * @method PdoWrapper db()
 * @method Permission permission()
 * @method string getUrl(string $route, array $params = [])
 * @method void render(string $template, array $data = [])
 * @method void redirect(string $url)
 */
abstract class BaseController
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
	 * Call method to create a shortcut to the $app property
	 * 
	 * @param string $name The method name
	 * @param array $arguments The method arguments
	 * 
	 * @return mixed
	 */
	public function __call(string $name, array $arguments)
	{
		return $this->app->$name(...$arguments);
	}

}
