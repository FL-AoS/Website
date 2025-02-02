<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/user_config.css">

	<title></title>
</head>
<body>
	@include("navbar")
	@if(!isset(auth()->user()->player_id))
		<h1>If you are logging for the first time and you had an account before, please do not create a new account and contact us on our discord: https://discord.gg/BJkMA49UQt</h1>
	@endif

	<form>
		<label for="config_login">Login:</label>
		<input id="config_login" type="text" name="login"><br>
		<label for="config_password">Password:</label>
		<input id="config_password" type="password" name="password">
	</form>
	<input type="checkbox" onclick="showpassword()">Show Password

	<script type="text/javascript">
		let cfg_log = document.getElementById("config_login");
		let cfg_pwd = document.getElementById("config_password");

		@if(isset(auth()->user()->player_id))
			cfg_log.value = "{{auth()->user()->player->login}}";
			cfg_pwd.value = "{{auth()->user()->player->password}}";
		@endif

		function showpassword() {
			if (cfg_pwd.type == "password")
				cfg_pwd.type = "text"
			else
				cfg_pwd.type = "password"
		}
	</script>
</body>
</html>