<?php

class LoginModel extends Model{
	
	private $sessionKey = 'login';
	
	public function createLoginSession($token){
		$_SESSION[$this->sessionKey] = $token;
	}
	
	public function getSessionToken(){
		return $_SESSION[$this->sessionKey];
	}
	
	public function destroyLoginSession(){
		unset($_SESSION[$this->sessionKey]);
	}

	public function loginSessionExists(){
		return (isset($_SESSION[$this->sessionKey]) && $_SESSION[$this->sessionKey] !== '') ? true : false;
	}
	
	public function generateToken(){
		return sha1(uniqid(rand(), true));
	}
	
	public function generateIdentifier(){
		return sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
	}
	
	public function generateCookieContent($user){
		$strIdentifier = $this->generateIdentifier();
		$strCookieValue = $user->getToken() . ':' . $strIdentifier;
		return $strCookieValue;
	}

	public function updateUserLoginData($user, $boolAddCookieTimeStamp){
		$user->setToken($this->generateToken());
		$user->setIp($_SERVER['REMOTE_ADDR']);
		$user->setAgent($_SERVER['HTTP_USER_AGENT']);
		if($boolAddCookieTimeStamp){
			$user->setCookieTime(time());
		}
		return $user;
	}
	
	public function checkAgent($user){
		return ($user->getAgent() === $_SERVER['HTTP_USER_AGENT']) ? true : false;
	}
	
	public function checkIp($user){
		return ($user->getIp() === $_SERVER['REMOTE_ADDR']) ? true : false;
	}
	
}