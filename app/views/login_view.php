<?php

class LoginView extends View{
	
	/**
	*	Constants for error and success messages
	*/
	const ERROR_COOKIE_LOGIN = 'Felaktig information i cookie.';
	const SUCCESS_COOKIE_LOGIN = 'Inloggning lyckades via cookies.';
	const ERROR_USERNAME_PASSWORD = 'Felaktigt användarnamn och/eller lösenord.';
	const ERROR_USERNAME = 'Användarnamn saknas.';
	const ERROR_PASSWORD = 'Lösenord saknas.';
	const SUCCESS_LOGIN = 'Inloggning lyckades.';
	const SUCCESS_LOGIN_REMEBER = 'Inloggning lyckades och vi kommer ihåg dig nästa gång.';
	const SUCCESS_LOGOUT = 'Du har nu loggat ut.';
	
	private $cookieName = 'auth';
	private $cookieTime = 2592000; //60*60*24*30 = 30 days
	
	private $keyUsername = 'username';
	private $keyPassword = 'password';
	private $keyRemeberMe = 'remeberme';
	
	private $model;
	
	private $boolLoggedInStatus = false;
	private $loggedInUser;

	public function __construct(LoginModel $model) {
		$this->model = $model;
	}

	// Kontrollerar om håll mig inloggad-checkboxen är markerad
	public function getRemeberMe(){
		return (isset($_POST[$this->keyRemeberMe])) ? true : false;
	}

	// Hämtar input från användarnamnsfältet
	public function getUsername(){
		return (isset($_POST[$this->keyUsername])) ? $_POST[$this->keyUsername] : '';
	}

	// Hämtar input från användarnamnsfältet
	public function getPassword(){

		return (isset($_POST[$this->keyPassword])) ? $_POST[$this->keyPassword] : '';
	}
	
	/**
	*	Check if there is an autgh cookie present
	*	@return bool
	*/
	public function authCookieExists(){
		return isset($_COOKIE[$this->cookieName]);
	}
	
	/**
	*	Destroys auth cookie
	*	@return void
	*/
	public function destroyAuthCookie(){
		unset($_COOKIE[$this->cookieName]);
		setcookie($this->cookieName, '', time() - 3600, '/');
	}
	
	/**
	*	Creates an auth cookie based on user data.
	*	@return void
	*/
	public function createAuthCookie($user){
		$strCookieContent = $this->model->generateCookieContent($user);
		setcookie($this->cookieName, $strCookieContent, time() + $this->cookieTime, '/');
	}
	
	/**
	*	Returns the value of auth cookie
	*	@return string
	*/
	public function getAuthCookie(){
		return $_COOKIE[$this->cookieName];
	}
	
	/**
	*	Return the amount of seconds an auth cookie is saved
	*	@return bool
	*/
	public function getAuthCookieTime(){
		return $this->cookieTime;
	}
	
	// Sätter aktuell tid och datum
	public function showDate() {
		setlocale(LC_ALL, 'sve');
		$setDateTime = utf8_encode(ucfirst(strftime("%A, den %d %B &aring;r %Y. Klockan &auml;r [%X]")));
		return $setDateTime;
	}
	
	// Visar inloggningssidan
	public function showLoginForm() {
		$name = isset($_POST[$this->keyUsername]) ? $_POST[$this->keyUsername] : '';
		$ret = "
			<div>
				<p><a href='?c=user&a=add'>Skapa användare</a></p>
			</div>
			<div>
			  	<form name='login' action='?c=Login' method='post' accept-charset='utf-8'>
			  		<fieldset>
						<legend>Login - Skriv in användarnamn och lösenord</legend>
						" . $this->getMessage() . "
						<div>
							<label for='$this->keyUsername'>Användarnamn</label>
							<input type='text' name='$this->keyUsername' value='$name' />
						</div>
						<div>
							<label for='$this->keyPassword'>Lösenord</label>
							<input type='password' name='$this->keyPassword' />
						</div>
						<div>
							Håll mig inloggad: <input type='checkbox' name='$this->keyRemeberMe' />
						</div>
						<div>
							<input type='submit' name='submit' value='Logga in'>
						</div>
					</fieldset>
				</form>
			</div>
		";

		return $ret;
	}

	// Visar inloggade sidan
	public function showUserLoggedInPage() {
		return '<p><a href="?c=login&a=logout">Logga ut<a/></p>';
	} 

	// Kontrollerar om användaren tryckt på logga in-knappen
	public function userPressedLogin(){
		return (isset($_POST[$this->keyUsername])) ? true : false;
	}
	
	/**
	*	Added View Methods
	*/
	
	public function setLoggedInStatus($b){
		$this->boolLoggedInStatus = $b;
	}
	
	public function setLoggedInUser($s){
		$this->loggedInUser = $s;
	}
	
	public function getViewHtml(){
		return $this->getStatusHtml() . $this->body . $this->showDate();
	}
	
	public function getStatusHtml(){
		if($this->boolLoggedInStatus){
			return '<h2>' . $this->loggedInUser . ' är inloggad</h2>';
		}
		return '<h2>Ej inloggad</h2>';
	}
}