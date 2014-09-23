<?php

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', dirname(__FILE__) . DS);
define('ROOT_PATH', '/' . basename(dirname(__FILE__)) . '/');

require_once(ROOT_DIR . 'lib' . DS . 'router.php');
require_once(ROOT_DIR . 'lib' . DS . 'viewHTML.php');

$router = new Router();
$html = $router->runApp();

$viewHTML = new viewHTML();
$viewHTML->showHTML($html);