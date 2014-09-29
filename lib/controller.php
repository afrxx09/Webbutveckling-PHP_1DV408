<?php
class Controller{
	
	public function redirectTo($controller = '', $action = ''){
		$location = (($controller != '') ? '?c=' . $controller : '') . (($action != '') ? '&a='. $action : '');
		header('location: ' . $location);
		die();
	}
}
?>