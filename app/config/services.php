<?php

use flight\Engine;
use flight\database\PdoWrapper;
use flight\debug\database\PdoQueryCapture;
use Tracy\Debugger;

/** 
 * @var array $config This comes from the returned array at the bottom of the config.php file
 * @var Engine $app
 */

// uncomment the following line for MySQL
// $dsn = 'mysql:host=' . $config['database']['host'] . ';dbname=' . $config['database']['dbname'] . ';charset=utf8mb4';

// uncomment the following line for SQLite
$dsn = 'sqlite:' . $config['database']['file_path'];

// In development, you'll want the class that captures the queries for you. In production, not so much.
$pdoClass = Debugger::$showBar === true ? PdoQueryCapture::class : PdoWrapper::class;
$app->register('db', $pdoClass, [ $dsn, $config['database']['user'] ?? null, $config['database']['password'] ?? null ]);

$Latte = new \Latte\Engine;
$Latte->setTempDirectory(__DIR__ . '/../cache/');
Latte\Bridges\Tracy\LattePanel::initialize($Latte);
$Latte->addFunction('route', function(string $alias, array $params = []) use ($app) {
	return $app->getUrl($alias, $params);
});
$Latte->addFunction('permission', function(string $permission, ...$args) use ($app) {
	return $app->permission()->has($permission, ...$args);
});

$app->map('render', function(string $templatePath, array $data = [], ?string $block = null) use ($app, $Latte) {
	$templatePath = __DIR__ . '/../views/'. $templatePath;
	// Add the username that's available in every template.
	$data = [
		'username' => $app->session()->getOrDefault('user', '')
	] + $data;
	$Latte->render($templatePath, $data, $block);
});

// Permissions
$currentRole = $app->session()->getOrDefault('role', 'guest');
$app->register('permission', \flight\Permission::class, [ $currentRole ]);
$permission = $app->permission();
$permission->defineRule('post', function(string $currentRole) {
    if($currentRole === 'admin') {
        $permissions = ['create', 'read', 'update', 'delete'];
    } else if($currentRole === 'editor') {
        $permissions = ['create', 'read', 'update'];
    } else {
        $permissions = ['read'];
    }
    return $permissions;
});
$permission->defineRule('comment', function(string $currentRole) {
	if($currentRole === 'admin') {
		$permissions = ['create', 'read', 'update', 'delete'];
	} else if($currentRole === 'editor') {
		$permissions = ['create', 'read', 'update'];
	} else {
		$permissions = ['read'];
	}
	return $permissions;
});