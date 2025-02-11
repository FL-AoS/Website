<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/profile.css">
	<title></title>
</head>
<body>
	@include("navbar")
	<div id="profile">
		<img id="discordpic" src="https://cdn.discordapp.com/avatars/{{$infos->discord_user->discord_id}}/{{$infos->discord_user->avatar_hash}}.png?size=512">
		<h1>{{$infos->player->login}}</h1>
		<h2>{{$infos->discord_user->username}}</h2>

		<table>
			<caption>Recent Runs</caption>
			<thead>
				<tr>
					<th class="date">Date</th>
					<th class="pos">#</th>
					<th class="time">time</th>
					<th class="map">Map Name</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</body>
</html>