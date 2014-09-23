<?php

class LoginView {
	
	/**
	*	Constants for error and success messages
	*/
	const MESSAGE_ERROR_COOKIE_LOGIN = 'Felaktig information i cookie.';
	const MESSAGE_SUCCESS_COOKIE_LOGIN = 'Inloggning lyckades via cookies.';
	const MESSAGE_ERROR_USERNAME_PASSWORD = 'Felaktigt användarnamn och/eller lösenord.';
	const MESSAGE_ERROR_USERNAME = 'Användarnamn saknas.';
	const MESSAGE_ERROR_PASSWORD = 'Lösenord saknas.';
	const MESSAGE_SUCCESS_LOGIN = 'Inloggning lyckades.';
	const MESSAGE_SUCCESS_LOGIN_REMEBER = 'Inloggning lyckades och vi kommer ihåg dig nästa gång.';
	const MESSAGE_SUCCESS_LOGOUT = 'Du har nu loggat ut.';
	
	private $strUsernameCookie = 'username';
	private $strPasswordCookie = 'password';
	private $strTokenCookie = 'cookietoken';
	private $intCookieTime = 86400;//1*24*60*60 = 1 day
	
	private $model;
	
	private $message = '';
	private $body = '';
	private $boolLoggedInStatus = false;
	private $loggedInUser;

	public function __construct(LoginModel $model) {
		$this->model = $model;
	}

	// Kontrollerar om håll mig inloggad-checkboxen är markerad
	public function checkBoxMarked(){
		return (isset($_POST['remember'])) ? true : false;
	}

	public function getCookieName(){
		return (isset($_COOKIE[$this->strUsernameCookie])) ? $_COOKIE[$this->strUsernameCookie] : null;
	}

	// Hämtar lösenord som är sparad i cookien
	public function getCookiePassword(){
		return (isset($_COOKIE[$this->strPasswordCookie])) ? $_COOKIE[$this->strPasswordCookie] : null;
	}

	// Hämtar token 
	public function getCookieToken(){
		return (isset($_COOKIE[$this->strTokenCookie])) ? $_COOKIE[$this->strTokenCookie] : null;
	}

	// Hämtar input från användarnamnsfältet
	public function getUsername(){
		return (!empty($_POST['Username'])) ? $_POST['Username'] : '';
	}

	// Hämtar input från användarnamnsfältet
	public function getPassword(){

		return (!empty($_POST['Password'])) ? $_POST['Password'] : '';
	}

	public function removeCookies(){
		if(isset($_COOKIE[$this->strUsernameCookie]) && isset($_COOKIE[$this->strPasswordCookie]) && isset($_COOKIE[$this->strPasswordCookie])) {
				setcookie($this->strUsernameCookie, '', time() - 3600, '/');
            	setcookie($this->strPasswordCookie, '', time() - 3600, '/');
            	setcookie($this->strTokenCookie, '', time() - 3600, '/');
        }
        return NULL; 
	}

	public function setCookieToken() {
		$cookieToken = crypt(date("1") . $_SERVER["HTTP_USER_AGENT"] . date("d"));
		return $cookieToken;
	}

	/* Skapar cookies för att hålla användaren inloggad om checkbox markerad
	och tar bort ev gamla cookies */
	public function keepUserLoggedIn() {
		if (!isset($_POST['remember'])) {
			if(isset($_COOKIE[$this->strUsernameCookie]) && isset($_COOKIE[$this->strPasswordCookie])) {
				setcookie($this->strUsernameCookie, '', time() - 3600, '/');
            	setcookie($this->strPasswordCookie, '', time() - 3600, '/');
            	setcookie($this->strTokenCookie, '', time() - 3600, '/');
            }
		}

		$passwordIsEncrypted = crypt($_POST['Password']);
		$cookieToken = $this->setCookieToken();

		setcookie($this->strUsernameCookie, $_POST['Username'], time() + $this->intCookieTime, '/');
		setcookie($this->strPasswordCookie, $passwordIsEncrypted, time() + $this->intCookieTime, '/');
		setcookie($this->strTokenCookie, $cookieToken, time() + $this->intCookieTime, '/');
	}

	// Sätter aktuell tid och datum
	public function showDate() {
		setlocale(LC_ALL, 'sve');
		$setDateTime = utf8_encode(ucfirst(strftime("%A, den %d %B &aring;r %Y. Klockan &auml;r [%X]")));
		return $setDateTime;
	}

	// Visar inloggningssidan
	public function showLoginForm() {
		$name = isset($_POST['Username']) ? $_POST['Username'] : '';
		$ret = "
		  	<form name='login' action='?c=Login' method='post' accept-charset='utf-8'>
				<div>
					<p>Login - Skriv in användarnamn och lösenord</p>
					<p><label for='username'>Användarnamn</label>
					<input type='username' name='Username' value='$name'></p>
					<p><label for='password'>Lösenord</label>
					<input type='password' name='Password'></p>
					<p>Håll mig inloggad: <input type='checkbox' name='remember' value='checkbox'></p>
					<p><input type='submit' name='submit' value='Logga in'></p>
				</div>
			</form>
		";

		return $ret;
	}

	// Visar inloggade sidan
	public function showUserLoggedInPage() {
		return "
			<form name='logout' action='?c=Login' method='post' accept-charset='utf-8'>
				<p><input type='submit' name='logout' value='Logga ut'></p>
			</form>
		";		
	} 

	// Kontrollerar om användaren tryckt på logga in-knappen
	public function userPressedLogin() {

		return (isset($_POST['submit'])) ? true : false;
	}

	// Kontrollerar om användaren tryckt på logga ut-knappen
	public function userPressedLogout() {
		return (isset($_POST['logout'])) ? true : false;
	}
	
	/**
	*	Added View Methods
	*/
	public function setMessage($s){
		$this->message = '<p>' . $s . '</p>';
	}
	
	public function setBody($s){
		$this->body = $s;
	}
	
	public function setLoggedInStatus($b){
		$this->boolLoggedInStatus = $b;
	}
	
	public function setLoggedInUser($s){
		$this->loggedInUser = $s;
	}
	
	public function renderHTML(){
		return $this->getStatusHtml() . $this->message . $this->body . $this->showDate();
	}
	
	public function getStatusHtml(){
		if($this->boolLoggedInStatus){
			return '<h2>' . $this->loggedInUser . ' är inloggad</h2>';
		}
		return '<h2>Ej inloggad</h2>';
	}
	
	public function getUsernameFromCookie(){
		return isset($_COOKIE[$this->strUsernameCookie]) ? $_COOKIE[$this->strUsernameCookie] : '';
	}
}