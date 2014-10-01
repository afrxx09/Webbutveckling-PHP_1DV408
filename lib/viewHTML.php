<?php
class viewHTML {
	public function showHTML($body) {
		echo '
			<!doctype html>
			<html>
				<head>
					<meta charset="utf-8">
			 		<title>Laboration 4 afrxx09</title>
				</head>
				<body>
					<h1>Laboration 4 afrxx09</h1>
				  	<div>
				  		' . $body . '
				  	</div>
				</body>
			</html>
		';		
	}
}