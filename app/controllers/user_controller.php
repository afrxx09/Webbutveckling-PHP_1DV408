<?php 

require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'user_model.php');
require_once(ROOT_DIR . 'app' . DS . 'views' . DS . 'user_view.php');

class UserController{
	
	private $view;
	private $model;

	public function __construct(){
		$this->model = new UserModel();
		$this->view = new UserView($this->model);
	}

	public function index(){
		return $this->add();
	}

	public function add(){
		return $this->view->add();
	}
}
?>