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
		$this->view = new LoginView($this->model, $this->usermodel);
	}
	
	/**
	*	Standard action function for a controller.
	*/
	public function index(){
		//simply redirect to doCheckLogin
		return $this->login();
	}
	
	/**
	*	Destroys sessions and cookie in order to signout
	*/
	public function logout(){
		$this->view->destroyAuthCookie();
		$this->model->destroyLoginSession();
		$this->view->setMessage(LoginView::SUCCESS_LOGOUT);
		$this->redirectTo('login');
	}
	
	/**
	*	Tries to sign in the user if the loginform was posted.
	*	Also creates a cookie for persistent login if that option was chosen by the user
	*/
	public function login() {		
		if($this->view->userPressedLogin() === true){
			if ($this->view->getUsername() === '') {
				$this->view->setMessage(LoginView::ERROR_USERNAME);
				$this->redirectTo('login');
			}
			if($this->view->getPassword() === ''){
				$this->view->setMessage(LoginView::ERROR_PASSWORD);
				$this->view->keepLoginFormUsername();
				$this->redirectTo('login');
			}
			
			$user = $this->usermodel->findBy('username', $this->view->getUsername());
			if($user === null || !$this->usermodel->auth($user, $this->view->getPassword())){
				$this->view->setMessage(LoginView::ERROR_USERNAME_PASSWORD);
				$this->view->keepLoginFormUsername();
				$this->redirectTo('login');
			}
			
			$boolRemeberMe = $this->view->getRemeberMe();
			$user = $this->model->updateUserLoginData($user, $boolRemeberMe);
			$this->usermodel->save($user);
			$this->model->createLoginSession($user);
			if($boolRemeberMe){
				$this->view->createAuthCookie($user);
				$this->view->setMessage(LoginView::SUCCESS_LOGIN_REMEBER);
			}
			else{
				$this->view->setMessage(LoginView::SUCCESS_LOGIN);
			}
			$this->redirectTo('login', 'successPage');
		}
		
		/**
		*	Fallback to login page just in case something fails
		*/
		return $this->loginPage();
	}
	
	/**
	*	@return string HTML for login-form unless user is signed in already
	*/
	private function loginPage(){
		if(!$this->checkSignIn()){
			return $this->view->loginPage();
		}
		else{
			$this->redirectTo('Login', 'successPage');
		}
		
	}
	
	/**
	*	@return string HTML for authenticated users page
	*/
	public function successPage(){
		if(!$this->checkSignIn()){
			$this->redirectTo('login');
		}
		$user = $this->usermodel->findBy('token', $this->model->getLoginSession());
		if($user === null){
			$this->model->destroyLoginSession();
			$this->redirectTo('login');
		}
		return $this->view->successPage();
	}
	
	/**
	*	Method to check if a user is signed in or has a persistent auth cookie that can be used to sign in
	*	@return bool
	*/
	public function checkSignIn(){
		if($this->model->loginSessionExists()){
			$user = $this->usermodel->findBy('token', $this->model->getLoginSession());
			if($user !== null && $this->model->authSession($user)){
				return true;
			}
			else{
				$this->view->setMessage(LoginView::ERROR_AGENT_IP);
				return false;
			}
		}
		if($this->view->authCookieExists() && $this->view->signInWithCookie()){
			return true;
		}
		return false;
	}
}
