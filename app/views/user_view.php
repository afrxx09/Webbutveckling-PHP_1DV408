<?php

class UserView extends View{
	const CREATE_USER_SUCCESS = 'Användare skapad.';
	const CREATE_USER_ERROR_USERNAME_LENGTH = 'Användarnamnet måste vara mellan 4 och 20 tecken.';
	const CREATE_USER_ERROR_USERNAME_UNALLOWED_CHARS = 'Otillåtna tecken i användarnamnet.';
	const CREATE_USER_ERROR_PASSORD_LENGTH = 'Lösenordet måste vara mellan 6 och 20 tecken.';
	const CREATE_USER_ERROR_PASSWORD_CONFIRM = 'Lösenordet och bekräftelsen stämmer inte överens.';
	const CREATE_USER_ERROR_PASSWORD_UNALLOWED_CHARS = 'Otillåtna tecken i Lösenordet.';

	private $model;

	public function __construct($model){
		$this->model = $model;
	}

	public function createFormPosted(){
		return isset($_POST['username']);
	}
	public function getUsername(){
		return isset($_POST['username']) ? strip_tags(trim($_POST['username'])) : null;
	}
	public function getPassword(){
		return isset($_POST['password']) ? strip_tags(trim($_POST['password'])) : null;
	}
	public function getPasswordConfirm(){
		return isset($_POST['password_confirm']) ? strip_tags(trim($_POST['password_confirm'])) : null;
	}

	public function getViewHtml(){
		return $this->message . $this->body;
	}

	public function add(){
		return '
			<form method="post" action="?c=User&a=create">
				<div class="">
					<label for="username">Användarnamn</label>
					<input type="text" name="username" id="username" />
				</div>
				<div class="">
					<label for="password">Lösenord</label>
					<input type="text" name="password" id="password" />
				</div>
				<div class="">
					<label for="password_confirm">Bekräfta lösenord</label>
					<input type="text" name="password_confirm" id="password_confirm" />
				</div>
				<div class="">
					<input type="submit" value="Skapa användare" /> 
				</div>
			</form>
		';
	}
}
?>