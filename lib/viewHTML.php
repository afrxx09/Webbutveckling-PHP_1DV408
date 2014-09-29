<?php
class viewHTML {

	public function showHTML($body) {

		echo '
			<!doctype html>
			<html>
				<head>
					<meta charset="utf-8">
			 		<title>Laboration 2 vl222cu</title>
				</head>
				<body>
					<h1>Laboration 2 vl222cu</h1>
				  	<div>
				  		' . $body . '
				  	</div>
				</body>
			</html>
		';		
	}
}
