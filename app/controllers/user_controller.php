<?php 

require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'user.php');
require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'user_model.php');
require_once(ROOT_DIR . 'app' . DS . 'views' . DS . 'user_view.php');

class UserController extends Controller{
	
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
		$this->view->setBody($this->view->add());
		return $this->view->getViewHtml();
	}

	public function create(){
		if($this->view->createFormPosted()){
			$username = $this->view->getUsername();
			$password = $this->view->getPassword();
			$password_confirm = $this->view->getPasswordConfirm();

			try{
				$this->model->create($username, $password, $password_confirm);
				$this->view->setMessage(UserView::CREATE_USER_SUCCESS);
				$this->redirectTo('login');
			}
			catch(Exception $e){
				$this->view->setMessage($e->getMessage());
				$this->redirectTo('user');
			}
		}
		$this->redirectTo('user');
	}
}
?>