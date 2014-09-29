<?php

require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'user.php');
require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'user_model.php');
require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'login_model.php');
require_once(ROOT_DIR . 'app' . DS . 'views' . DS . 'login_view.php');


class LoginController extends Controller{
	private $model;
	private $view;  

	public function __construct() {
		$this->model = new LoginModel();
		$this->usermodel = new UserModel();
		$this->view = new LoginView($this->model);
	}
	
	/**
	*	Standard action function for a controller.
	*/
	public function index(){
		//simply redirect to doCheckLogin
		return $this->login();
	}
	
	public function logout(){
		$this->view->destroyAuthCookie();
		$this->model->logout();
		$this->view->setMessage(LoginView::SUCCESS_LOGOUT);
		return $this->loginPage();
	}
	
	public function login() {
		
		$this->checkLogin();
		
		if($this->view->userPressedLogin() == true){
			if ($this->view->getUsername() === '') {
				$this->view->setMessage(LoginView::ERROR_USERNAME);
				$this->redirectTo('login');
			}
			if($this->view->getPassword() === ''){
				$this->view->setMessage(LoginView::ERROR_PASSWORD);
				$this->redirectTo('login');
			}
			
			try{
				$user = $this->usermodel->findBy('username', $this->view->getUsername());
			}
			catch(Exception $e){
				$this->view->setMessage($e->getMessage());
				$this->redirectTo('login');
			}
			
			if(!$this->usermodel->auth($user, $this->view->getPassword())){
				$this->view->setMessage(LoginView::ERROR_USERNAME_PASSWORD);
				$this->redirectTo('login');
			}
			
			$this->model->login($user);
			$this->view->setMessage(LoginView::SUCCESS_LOGIN);
			$this->view->setLoggedInStatus(true);
			$this->view->setLoggedInUser($user->getUsername());
			$this->redirectTo('login');
			
			/*
			if ($this->model->login($this->view->getUsername(), $this->view->getPassword())) {
				
				if ($this->view->checkBoxMarked()) {
					$this->view->setMessage(LoginView::SUCCESS_LOGIN_REMEBER);
					$this->view->keepUserLoggedIn();
				}
				else {
					$this->view->setMessage(LoginView::SUCCESS_LOGIN);
				}
				
				$this->view->setLoggedInStatus(true);
				$this->view->setLoggedInUser($this->model->getSessionUsername());
				$this->redirectTo('login');

			} else {
					
				if ($this->view->getUsername() == "") {
					$this->view->setMessage(LoginView::ERROR_USERNAME);

				}
				else if ($this->view->getPassword() == "") {
					$this->view->setMessage(LoginView::ERROR_PASSWORD);
				}
				else {
					$this->view->setMessage(LoginView::ERROR_USERNAME_PASSWORD);
				} 
				
				$this->view->setLoggedInStatus(false);
				return $this->loginPage();
			}
			*/

		}
		/*
		//Inloggad via håll mig inloggad-checkboxen
		if ($this->view->getCookieName() !== null && $this->view->getCookiePassword() !== null) {

			$token = $this->view->setCookieToken();

			if ($token == $this->view->getCookieToken()) {
				$this->view->setLoggedInStatus(true);
				$this->view->setLoggedInUser($this->model->getSessionUsername());
				$this->view->setMessage(LoginView::SUCCESS_COOKIE_LOGIN);
				return $this->successPage();

			}
			else {
				$this->view->setLoggedInStatus(false);
				$this->view->setMessage(LoginView::ERROR_COOKIE_LOGIN);
				return $this->loginPage();
			}			
		}
		
		/**
		*	Fallback to login page just in case something fails
		*/
		return $this->loginPage();
	}
	
	/**
	*	Added Controller Methods
	*/
	
	private function loginPage(){
		$this->view->setBody($this->view->showLoginForm());
		return $this->view->getViewHtml();
	}
	
	public function successPage(){
		$this->view->setLoggedInStatus(true);
		$this->view->setLoggedInUser($this->model->getSessionUsername());
		$this->view->setBody($this->view->showUserLoggedInPage());
		return $this->view->getViewHtml();
	}
	
	private function checkLogin(){
		// Användaren är inloggad via session
		if ($this->model->userIsLoggedIn()) {
			$this->view->setLoggedInStatus(true);
			$this->view->setLoggedInUser($this->model->getSessionUsername());
			$this->redirectTo('login', 'successPage');
		}
	}
}
