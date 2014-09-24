<?php

class UserView extends View{
	
	private $model;

	public function __construct($model){
		$this->model = $model;
	}


	public function getViewHtml(){
		return $this->message . $this->body;
	}

	public function add(){
		return '
			<form method="post" action="?c=User&a=add">
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