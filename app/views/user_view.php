<?php

class UserView extends View{
	const CREATE_USER_SUCCESS = 'Användare skapad.';
	const CREATE_USER_ERROR_USERNAME_LENGTH = 'Användarnamnet måste vara mellan 4 och 20 tecken.';
	const CREATE_USER_ERROR_USERNAME_UNALLOWED_CHARS = 'Otillåtna tecken i användarnamnet.';
	const CREATE_USER_ERROR_PASSORD_LENGTH = 'Lösenordet måste vara mellan 6 och 20 tecken.';
	const CREATE_USER_ERROR_PASSWORD_CONFIRM = 'Lösenordet och bekräftelsen stämmer inte överens.';
	const CREATE_USER_ERROR_PASSWORD_UNALLOWED_CHARS = 'Otillåtna tecken i Lösenordet.';
	const CREATE_USER_ERROR_DUPLICATE_USERNAME = "Användarnamnet är upptaget.";

	private $model;
	private $keyUsername = 'username';
	private $keyPassword = 'password';
	private $keyPasswordConfirm = 'password_confirm';

	public function __construct($model){
		$this->model = $model;
	}

	public function createFormPosted(){
		return isset($_POST[$this->keyUsername]);
	}
	public function getUsername(){
		return isset($_POST[$this->keyUsername]) ? strip_tags(trim($_POST[$this->keyUsername])) : '';
	}
	public function getPassword(){
		return isset($_POST[$this->keyPassword]) ? strip_tags(trim($_POST[$this->keyPassword])) : '';
	}
	public function getPasswordConfirm(){
		return isset($_POST[$this->keyPasswordConfirm]) ? strip_tags(trim($_POST[$this->keyPasswordConfirm])) : '';
	}

	public function getViewHtml(){
		return $this->getMessage() . $this->body;
	}

	public function add(){
		return '
			<div>
				<p><a href="?">Logga in</a></p>
			</div>
			<div>
				<form method="post" action="?c=User&a=create">
					<div class="">
						<label for="' . $this->keyUsername . '">Användarnamn</label>
						<input type="text" name="' . $this->keyUsername . '" id="' . $this->keyUsername'" />
					</div>
					<div class="">
						<label for="' . $this->keyPassword . '">Lösenord</label>
						<input type="text" name="' . $this->keyPassword . '" id="' . $this->keyPassword . '" />
					</div>
					<div class="">
						<label for="' . $this->keyPasswordConfirm . '">Bekräfta lösenord</label>
						<input type="text" name="' . $this->keyPasswordConfirm . '" id="' . $this->keyPasswordConfirm . '" />
					</div>
					<div class="">
						<input type="submit" value="Skapa användare" /> 
					</div>
				</form>
			</div>
		';
	}
}
?>