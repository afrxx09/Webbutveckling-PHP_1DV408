<?php

class User(){
	private $id;
	private $username;
	private $password;

	public function __construct($username, $password){
		$this->username = $username;
		$this->password = $password;
	}

	/**
	*	Getters
	*/
	public function getId(){
		return $this->id;
	}
	public function getUsername(){
		return $this->username;
	}
	public function getPassword(){
		return $this->password;
	}

	/**
	*	Setters
	*/
	public function setUsername($s){
		$this->username = '' . $s;
	}
	public function setPassword($s){
		$this->password = '' . $s;
	}
}
?>