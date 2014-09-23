<?php

class LoginModel {

	// Kontroll av sessionvariabeln
	public function getSessionUsername(){
		return (isset($_SESSION['username'])) ? $_SESSION['username'] : '';
	}

	// Kontroll av inloggningsuppgifter
	public function login($username, $password) {
		if ($username =='Admin' && $password =='Password'){
				$_SESSION['LoggedIn'] = true;
				$_SESSION['username'] = $username;
				return true;	
		}
		return false;
	} 

	// Utloggning
	public function logOut() {
		try{
			unset($_SESSION['LoggedIn']);
	  		unset($_SESSION['username']);
		}
		catch(Exception $e){
			//Just to make sure application does not break in case sessions doesn't exists / are deleted / tamperd with etc.
		}
	}

	// Kontrollerar om användaren är inloggad
	public function userIsLoggedIn() {
		return (isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn']) ? true : false;
	}
}



