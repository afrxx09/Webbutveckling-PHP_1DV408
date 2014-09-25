<?php
class Controller{
	
	public function redirectTo($controller = '', $action = ''){
		$location = ROOT_PATH . (($controller != '') ? $controller . '/' : '') . (($action != '') ? $action . '/' : '');
		header('location: ' . $locaton);
		die();
	}
}
?>