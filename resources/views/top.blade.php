<!DOCTYPE html>
<html lang="jp">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Gladiator</title>
		<link href="{{APP_URL}}css/reset.css?var={{time()}}" rel="stylesheet" type="text/css">
		<link href="{{APP_URL}}css/style.css?var={{time()}}" rel="stylesheet" type="text/css">
		<link href="{{APP_URL}}css/modal.css?var={{time()}}" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div>
			<img src="{{IMG_URL}}title/background.png" class="top_back">
		</div>
		@include('common/css', ['file' => 'top'])
		<div>
			<img class = "title_logo"src="{{IMG_URL}}title/titlelogo.png">
		</div>
		<div>
			<a  href="{{APP_URL}}top/login"><img class = "top_button top_login"src="{{IMG_URL}}title/loginbutton.png"></a>
		</div>
	</body>
</html>
