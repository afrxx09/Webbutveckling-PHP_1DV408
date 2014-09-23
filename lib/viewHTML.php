<?php
//header('Content-Type: text/html; charset=utf-8');
class viewHTML {

	public function showHTML($body) {

		echo '
			<!DOCTYPE html>
			<html>
			<head>
			 	<title>Laboration 2 vl222cu</title>
			 	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			</head>
			<body>
				<h1>Laboration 2 vl222cu</h1>
			  	<p>' . $body . '</p>
			</body>
			</html>
		';		
	}
}
