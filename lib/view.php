<?php

abstract class View{
	
	protected $message;
	protected $body;

	public function setBody($s){
		$this->body = $s;
	}

	public function setMessage($s){
		$this->message = '<p>' . $s . '</p>';
	}

	abstract public function getViewHtml();
}
?>