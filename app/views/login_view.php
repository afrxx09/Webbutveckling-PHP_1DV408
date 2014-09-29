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
	
	/*
	private $strUsernameCookie = 'username';
	private $strPasswordCookie = 'password';
	private $strTokenCookie = 'cookietoken';
	private $intCookieTime = 86400;//1*24*60*60 = 1 day
	*/
	private $keyCookieToken = 'cookietoken';
	private $cookietime = 2592000; //60*60*24*30 = 30 days
	
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

	public function getCookieName(){
		return (isset($_COOKIE[$this->strUsernameCookie])) ? $_COOKIE[$this->strUsernameCookie] : '';
	}

	// Hämtar lösenord som är sparad i cookien
	public function getCookiePassword(){
		return (isset($_COOKIE[$this->strPasswordCookie])) ? $_COOKIE[$this->strPasswordCookie] : '';
	}

	// Hämtar token 
	public function getCookieToken(){
		return (isset($_COOKIE[$this->strTokenCookie])) ? $_COOKIE[$this->strTokenCookie] : '';
	}

	// Hämtar input från användarnamnsfältet
	public function getUsername(){
		return (isset($_POST[$this->keyUsername])) ? $_POST[$this->keyUsername] : '';
	}

	// Hämtar input från användarnamnsfältet
	public function getPassword(){

		return (isset($_POST[$this->keyPassword])) ? $_POST[$this->keyPassword] : '';
	}
	
	public function destroyAuthCookie(){
		unset($_COOKIE[$this->keyCookieToken]);
		setcookie($this->keyCookieToken, '', time() - 3600, '/');
	}
	
	public function createAuthCookie($token, $identifier){
		$content = $token . ':' . $identifier;
		setcookie($this->keyCookieToken, $content, time() + $this->cookietime, '/');
	}
	/*
	public function removeCookies(){
		if(isset($_COOKIE[$this->strUsernameCookie]) && isset($_COOKIE[$this->strPasswordCookie]) && isset($_COOKIE[$this->strPasswordCookie])) {
				setcookie($this->strUsernameCookie, '', time() - 3600, '/');
            	setcookie($this->strPasswordCookie, '', time() - 3600, '/');
            	setcookie($this->strTokenCookie, '', time() - 3600, '/');
        }
        return NULL; 
	}
	*/
	/*
	public function setCookieToken() {
		$cookieToken = crypt(date("1") . $_SERVER["HTTP_USER_AGENT"] . date("d"));
		return $cookieToken;
	}
	*/
	/* Skapar cookies för att hålla användaren inloggad om checkbox markerad
	och tar bort ev gamla cookies */
	/*
	public function keepUserLoggedIn() {
		if(isset($_COOKIE[$this->strUsernameCookie]) && isset($_COOKIE[$this->strPasswordCookie])) {
			setcookie($this->strUsernameCookie, '', time() - 3600, '/');
        	setcookie($this->strPasswordCookie, '', time() - 3600, '/');
        	setcookie($this->strTokenCookie, '', time() - 3600, '/');
        }

		$passwordIsEncrypted = crypt($_POST[$this->keyPassword]);
		$cookieToken = $this->setCookieToken();

		setcookie($this->strUsernameCookie, $_POST[$this->keyUsername], time() + $this->intCookieTime, '/');
		setcookie($this->strPasswordCookie, $passwordIsEncrypted, time() + $this->intCookieTime, '/');
		setcookie($this->strTokenCookie, $cookieToken, time() + $this->intCookieTime, '/');
	}
	*/
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
					<div>
						<p>Login - Skriv in användarnamn och lösenord</p>
					</div>
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
		return $this->getStatusHtml() . $this->getMessage() . $this->body . $this->showDate();
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