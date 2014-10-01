<?php

class View{
	
	protected $messageKey = 'message';
	protected $body;

	public function setBody($s){
		$this->body = $s;
	}

	public function setMessage($s){
		$_SESSION[$this->messageKey] =  $s;
	}
	
	public function getMessage(){
		if(isset($_SESSION[$this->messageKey])){
			$message = $_SESSION[$this->messageKey];
			unset($_SESSION[$this->messageKey]);
			return '<p>' . $message . '</p>';
		}
		return '';
	}
}