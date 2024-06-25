<?php

use app\middlewares\LoginMiddleware;
use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */
// Home Page
$router->get('/', \app\controllers\HomeController::class . '->index')->setAlias('home');

// Blog
$router->group('/blog', function(Router $router) {

	// Login
	$router->get('/login', \app\controllers\LoginController::class . '->index')->setAlias('login');
	$router->post('/login', \app\controllers\LoginController::class . '->authenticate')->setAlias('login_authenticate');
	$router->get('/logout', \app\controllers\LogoutController::class . '->index')->setAlias('logout');

	// And empty group just simply groups together routes where you can assign a middleware.
	$router->group('', function(Router $router) {

		// Protected Posts
		$router->get('/create', \app\controllers\PostController::class . '->create')->setAlias('blog_create');
		$router->post('', \app\controllers\PostController::class . '->store')->setAlias('blog_store');
		$router->get('/@id/edit', \app\controllers\PostController::class . '->edit')->setAlias('blog_edit');
		$router->post('/@id/edit', \app\controllers\PostController::class . '->update')->setAlias('blog_update');
		$router->get('/@id/delete', \app\controllers\PostController::class . '->destroy')->setAlias('blog_destroy');

		// Comments
		$router->post('/@id/comment', \app\controllers\CommentController::class . '->store')->setAlias('comment_store');
		$router->get('/@id/comment/@comment_id/delete', \app\controllers\CommentController::class . '->destroy')->setAlias('comment_destroy');
	}, [ LoginMiddleware::class ]);

	// Public Posts Area
	$router->get('', \app\controllers\PostController::class . '->index')->setAlias('blog');
	// We moved this to the bottom so that it doesn't conflict with the other routes like /create
	$router->get('/@id', \app\controllers\PostController::class . '->show')->setAlias('blog_show');

});