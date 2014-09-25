<?php

class UserModel extends Model{
	public function __construct(){

	}

	public function create($username, $password, $password_confirm){
		$username = $this->checkUsername();
		$password = $this->checkPassword($password, $password_confirm);
		return new User($username, $password);
	}

	private function checkUsername($u){
		$username = preg_replace('/[^a-z0-9]/i', '', $u);
		if($username !== $u){
			throw new Exception(UserView::CREATE_USER_ERROR_USERNAME_UNALLOWED_CHARS);
		}
		if(strlen($username) > 20 || strlen($username) < 4){
			throw new Exception(UserView::CREATE_USER_ERROR_USERNAME_LENGTH);
		}
		return $username;
	}

	private function checkPassword($p, $p_c){
		$password = preg_replace('/[^a-z0-9]/i', '', $p);
		$password_confirm = preg_replace('/[^a-z0-9]/i', '', $p_c);
		if($password !== $p || $password_confirm !== $p_c){
			throw new Exception(UserView::CREATE_USER_ERROR_PASSWORD_UNALLOWED_CHARS);
		}
		if($password !== $password_confirm){
			throw new Exception(UserView::CREATE_USER_ERROR_PASSWORD_CONFIRM);
		}
		if(strlen($password) > 20 || strlen($password) < 4){
			throw new Exception(UserView::CREATE_USER_ERROR_PASSORD_LENGTH);
		}
		return $password;
	}
}
?>