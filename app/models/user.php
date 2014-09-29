<?php

class User{
	private $id;
	private $username;
	private $password;
	private $token;
	private $ip;
	private $agent;
	
	public function __construct($userArray){
		$this->id = $userArray['id'];
		$this->username = $userArray['username'];
		$this->password = $userArray['password'];
		$this->token = $userArray['token'];
		$this->ip = $userArray['ip'];
		$this->agent = $userArray['agent'];
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