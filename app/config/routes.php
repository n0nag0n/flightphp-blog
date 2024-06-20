<?php

use flight\Engine;
use flight\net\Router;

/** 
 * @var Router $router 
 * @var Engine $app
 */
$router->get('/', \app\controllers\HomeController::class . '->index');
$router->group('/blog', function(Router $router) {
	$router->get('', \app\controllers\PostController::class . '->index');
	$router->get('/create', \app\controllers\PostController::class . '->create');
	$router->post('', \app\controllers\PostController::class . '->store');
	$router->get('/@id', \app\controllers\PostController::class . '->show');
	$router->get('/edit/@id', \app\controllers\PostController::class . '->edit');
	$router->post('/edit/@id', \app\controllers\PostController::class . '->update');
	$router->get('/delete/@id', \app\controllers\PostController::class . '->destroy');

	$router->post('/@id/comment', \app\controllers\CommentController::class . '->store');
	$router->get('/@id/comment/@comment_id/delete', \app\controllers\CommentController::class . '->destroy');
});