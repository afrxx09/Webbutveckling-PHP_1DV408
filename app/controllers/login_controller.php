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
		$this->model->destroyLoginSession();
		$this->view->setMessage(LoginView::SUCCESS_LOGOUT);
		return $this->loginPage();
	}
	
	public function login() {		
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
			
			$boolRemeberMe = $this->view->getRemeberMe();
			$user = $this->model->updateUserLoginData($user, $boolRemeberMe);
			$this->usermodel->save($user);
			if($boolRemeberMe){
				$this->view->createAuthCookie($user);
				$this->view->setMessage(LoginView::SUCCESS_LOGIN_REMEBER);
			}
			else{
				$this->view->setMessage(LoginView::SUCCESS_LOGIN);
			}
			
			$this->model->createLoginSession($user->getToken());
			$this->view->setLoggedInStatus(true);
			$this->view->setLoggedInUser($user->getUsername());
			$this->redirectTo('login', 'successPage');
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
		if(!$this->checkSignIn()){
			$this->view->setBody($this->view->showLoginForm());
			return $this->view->getViewHtml();
		}
		else{
			$this->redirectTo('Login', 'successPage');
		}
		
	}
	
	public function successPage(){
		if(!$this->checkSignIn()){
			$this->redirectTo('login');
		}
		else{
			$this->view->setLoggedInStatus(true);
			$user = $this->usermodel->findBy('token', $this->model->getSessionToken());
			$this->view->setLoggedInUser($user->getUsername());
			$this->view->setBody($this->view->showUserLoggedInPage());
			return $this->view->getViewHtml();
		}
	}
	
	/**
	*	Method to check if a user is signed in or has a persistent auth cookie that can be used to sign in
	*/
	public function checkSignIn(){
		$boolSuccess = false;
		if($this->model->loginSessionExists()){
			$user = $this->usermodel->findBy('token', $this->model->getSessionToken());
			if($user !== null){
				//Check if the User agent is the same in the DB as on the client
				if(!$this->model->checkAgent($user)){
					//$this->view->addFlash(\View\LoginView::UnknownAgent, \View::FlashClassError);
				}
				//Check the IP-address from DB and client
				else if(!$this->model->checkIp($user)){
					//$this->view->addFlash(\View\LoginView::UnknownIp, \View::FlashClassError);
				}
				else{
					$boolSuccess = true;
				}
			}
		}
		else{
			if($this->view->authCookieExists()){
				if(!$this->signInWithCookie()){
					//$this->view->addFlash(\View\LoginView::CookieLoginFail, \View::FlashClassError);
				}
				else{
					//$this->view->addFlash(\View\LoginView::CookieLogin, \View::FlashClassSuccess);
					$boolSuccess = true;
				}
			}
				
		}
		return $boolSuccess;
	}
	
	/**
	*	Method for signing in a user with an auth cookie
	*/
	public function signInWithCookie(){
		$arrCookie = explode(':', $this->view->getAuthCookie());
		$strCookieToken = $arrCookie[0];
		$strCookieIdentifier = $arrCookie[1];
		$user = $this->usermodel->findBy('token', $strCookieToken);
		if($user !== null){
			$strCurrentVisitorIdentifier = $this->model->generateIdentifier();
			//Compare identification string from cookie to newly generated one
			if($strCurrentVisitorIdentifier === $strCookieIdentifier){
				//Check in database on user when cookie was created, add the amount of time the view saves cookies.(time cookie was created + 30 days)
				//If the time right now is less than that(time created + 30 days) it's presumed that the cookie expire date has been tampered with
				if(($user->getCookieTime() + $this->view->getAuthCookieTime()) > time()){
					$this->model->createLoginSession($user->getToken());
					return true;
				}
			}
		}
		return false;
	}
}
