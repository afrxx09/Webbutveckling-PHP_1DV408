<?php

class UserModel extends Model{
	
	public function __construct(){
		$this->tabelName = 'user';
		$this->columns = array('id', 'username', 'password', 'token', 'ip', 'agent', 'cookietime');
	}
	
	public function save($user){
		try{
			$con = $this->connection();
			$sql = "
				UPDATE
					" . $this->tabelName . "
				SET
					" . $this->tabelName . "." . $this->columns[1] . " = ?,
					" . $this->tabelName . "." . $this->columns[2] . " = ?,
					" . $this->tabelName . "." . $this->columns[3] . " = ?,
					" . $this->tabelName . "." . $this->columns[4] . " = ?,
					" . $this->tabelName . "." . $this->columns[5] . " = ?,
					" . $this->tabelName . "." . $this->columns[6] . " = ?
				WHERE
					" . $this->tabelName . "." . $this->columns[0] . " = ?
			";
			$params = array($user->getUsername(), $user->getPassword(), $user->getToken(), $user->getIp(), $user->getAgent(), $user->getCookieTime(), $user->getId());
			
			$query = $con->prepare($sql);
			$query->execute($params);
		}
		catch(PDOException $e){
			if(intval($e->getCode()) === 23000){
				throw new Exception(UserView::CREATE_USER_ERROR_DUPLICATE_USERNAME);
			}
			throw new Exception($e->getMessage());
		}
	}
	
	public function create($username, $password, $password_confirm){
		$username = $this->checkUsername($username);
		$password = $this->checkPassword($password, $password_confirm);
		$password = $this->getScrambledPassword($password);
		try{
			$con = $this->connection();
			$sql = "
				INSERT INTO
					" . $this->tabelName . " (" . $this->columns[1] . ", " . $this->columns[2] . ")
				VALUES
					(?,?)
			";
			$params = array($username, $password);
			
			$query = $con->prepare($sql);
			$query->execute($params);
		}
		catch(PDOException $e){
			if(intval($e->getCode()) === 23000){
				throw new Exception(UserView::CREATE_USER_ERROR_DUPLICATE_USERNAME);
			}
			throw new Exception($e->getMessage());
		}
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
	
	public function auth($user, $password){
		return ($user->getPassword() === $this->getScrambledPassword($password)) ? true : false;
	}
	
	private function getScrambledPassword($password){
		//Will make more complex if there is time.
		$salt = 'asd123';
		return sha1($salt . $password);
	}
}