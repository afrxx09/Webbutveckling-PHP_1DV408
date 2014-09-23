<?php

require_once(ROOT_DIR . 'app' . DS . 'models' . DS . 'LoginModel.php');
require_once(ROOT_DIR . 'app' . DS . 'views' . DS . 'LoginView.php');


class LoginController {
	private $model;
	private $view;  

	public function __construct() {
		$this->model = new LoginModel();
		$this->view = new LoginView($this->model);
	}
	
	/**
	*	Standard action function for a controller.
	*/
	public function index(){
		//simply redirect to doCheckLogin
		return $this->doCheckLogin();
	}
	
	public function doCheckLogin() {
		// Utloggningscase
		if ($this->view->userPressedLogout()) {
			$this->view->removeCookies();
			$this->model->logout();
			$this->view->setMessage(LoginView::MESSAGE_SUCCESS_LOGOUT);
			return $this->loginPage();
		}

		// Användaren är inloggad via session
		if ($this->model->userIsLoggedIn()) {
			$this->view->setLoggedInStatus(true);
			$this->view->setLoggedInUser($this->model->getSessionUsername());
			return $this->successPage();

		}
		
		if ($this->view->userPressedLogin() == true) {

			if ($this->model->login($this->view->getUsername(), $this->view->getPassword())) {

				if ($this->view->checkBoxMarked()) {
					$this->view->setMessage(LoginView::MESSAGE_SUCCESS_LOGIN_REMEBER);
					$this->view->keepUserLoggedIn();
				}
				else {
					$this->view->setMessage(LoginView::MESSAGE_SUCCESS_LOGIN);
				}
				
				$this->view->setLoggedInStatus(true);
				$this->view->setLoggedInUser($this->model->getSessionUsername());
				return $this->successPage();

			} else {
					
				if ($this->view->getUsername() == "") {
					$this->view->setMessage(LoginView::MESSAGE_ERROR_USERNAME);

				}
				else if ($this->view->getPassword() == "") {
					$this->view->setMessage(LoginView::MESSAGE_ERROR_PASSWORD);
				}
				else {
					$this->view->setMessage(LoginView::MESSAGE_ERROR_USERNAME_PASSWORD);
				} 
				
				$this->view->setLoggedInStatus(false);
				return $this->loginPage();
			}

		}

		//Inloggad via håll mig inloggad-checkboxen
		if ($this->view->getCookieName() !== null && $this->view->getCookiePassword() !== null) {

			$token = $this->view->setCookieToken();

			if ($token == $this->view->getCookieToken()) {
				$this->view->setLoggedInStatus(true);
				$this->view->setLoggedInUser($this->model->getSessionUsername());
				$this->view->setMessage(LoginView::MESSAGE_SUCCESS_COOKIE_LOGIN);
				return $this->successPage();

			}
			else {
				$this->view->setLoggedInStatus(false);
				$this->view->setMessage(LoginView::MESSAGE_ERROR_COOKIE_LOGIN);
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
		return $this->view->renderHTML();
	}
	
	private function successPage(){
		$this->view->setBody($this->view->showUserLoggedInPage());
		return $this->view->renderHTML();
	}
}
