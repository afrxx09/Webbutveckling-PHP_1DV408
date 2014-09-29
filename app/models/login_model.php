<?php

class LoginModel {

	// Kontroll av sessionvariabeln
	public function getSessionUsername(){
		return (isset($_SESSION['username'])) ? $_SESSION['username'] : '';
	}

	public function login($user){
		$_SESSION['token'] = true;
		$_SESSION['username'] = $user->getUserName();
	}

	// Utloggning
	public function logOut() {
		try{
			unset($_SESSION['token']);
	  		unset($_SESSION['username']);
		}
		catch(Exception $e){
			//Just to make sure application does not break in case sessions doesn't exists / are deleted / tamperd with etc.
		}
	}

	// Kontrollerar om användaren är inloggad
	public function userIsLoggedIn() {
		return (isset($_SESSION['token'])) ? true : false;
	}
}



