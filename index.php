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
define('DB_CONNECTION_STRING', 'mysql:host=127.0.0.1;dbname=lab4');
define('DB_USERNAME', 'afrxx09');
define('DB_PASSWORD', 'lnustudent');

/**
*	Include core classes for application
*/
require_once(ROOT_DIR . 'lib' . DS . 'router.php');
require_once(ROOT_DIR . 'lib' . DS . 'viewHTML.php');

require_once(ROOT_DIR . 'lib' . DS . 'view.php');
require_once(ROOT_DIR . 'lib' . DS . 'controller.php');
require_once(ROOT_DIR . 'lib' . DS . 'model.php');

$router = new Router();
$html = $router->runApp();

$viewHTML = new viewHTML();
$viewHTML->showHTML($html);