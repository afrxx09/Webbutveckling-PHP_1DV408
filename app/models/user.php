<?php

class User{
	private $id;
	private $username;
	private $password;
	private $token;
	private $ip;
	private $agent;
	private $cookietime;
	
	public function __construct($userArray){
		$this->id = $userArray['id'];
		$this->username = $userArray['username'];
		$this->password = $userArray['password'];
		$this->token = $userArray['token'];
		$this->ip = $userArray['ip'];
		$this->agent = $userArray['agent'];
		$this->cookietime = $userArray['cookietime'];
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
	public function getToken(){
		return $this->token;
	}
	public function getIp(){
		return $this->ip;
	}
	public function getAgent(){
		return $this->agent;
	}
	public function getCookieTime(){
		return $this->cookietime;
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
	public function setToken($s){
		$this->token = '' . $s;
	}
	public function setIp($s){
		$this->ip = '' . $s;
	}
	public function setAgent($s){
		$this->agent = '' . $s;
	}
	public function setCookieTime($i){
		$this->cookietime = intval($i);
	}
	
}