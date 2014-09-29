<?php

require_once(ROOT_DIR . 'lib' . DS . 'view.php');
require_once(ROOT_DIR . 'lib' . DS . 'controller.php');
require_once(ROOT_DIR . 'lib' . DS . 'model.php');

class Router{
	private $controller;
	private $action;
	
	/**
	*	Takes the $_GET from request to figure out what controller and method in that controller to run.
	*/
	public function __construct(){
		$this->controller = isset($_GET['c']) ? $_GET['c'] : 'login';
		$this->action = isset($_GET['a']) ? $_GET['a'] : '';
	}
	
	/**
	*	Runs selected controller and its action and returns the views html for body-html
	*	@return string 
	*/
	public function runApp(){
		$controller = $this->loadController();
		$action = (method_exists($controller, $this->action)) ? $this->action : 'index';
		return call_user_func(array($controller, $action));
	}
	
	/**
	*	Looks for the controller file and loads it, then makes sure the class exists and instantiates it and returns the object
	*	@return controller-object
	*/
	private function loadController(){
		$controllerPath = ROOT_DIR . 'app' . DS . 'controllers' . DS . strtolower($this->controller) . '_controller.php';
		if(file_exists($controllerPath)){
			require_once($controllerPath);
			$controllerClassName = ucfirst($this->controller) . 'Controller';
			if(class_exists($controllerClassName)){
				return new $controllerClassName();
			}
			throw new Exception('Could not load controller class:' . strtolower($this->controller) . '_controller.php');
		}
		throw new Exception('Could not find controller class file.' . $this->controller);
	}
}
?>