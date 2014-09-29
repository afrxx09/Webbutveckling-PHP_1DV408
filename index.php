<?php

session_start();

/**
*	Define directory stuff
*/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__) . DS);
define('ROOT_PATH', '/' . basename(dirname(__FILE__)) . '/');

/**
*	Define databse stuff
*/


/**
*	Include core classes for application
*/
require_once(ROOT_DIR . 'cfg' . DS . 'config.php');
require_once(ROOT_DIR . 'lib' . DS . 'router.php');
require_once(ROOT_DIR . 'lib' . DS . 'viewHTML.php');

try{
	$router = new Router();
	$html = $router->runApp();

	$viewHTML = new viewHTML();
	$viewHTML->showHTML($html);
}
catch(\Exception $e){
	if(Config::$DEV_MODE){
		var_dump($e);
	}
	else{
		error_log('[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . "\n", 3 , ROOT_DIR . 'error_log_deploy.log');
		header('location: 404.html');
	}
	
}