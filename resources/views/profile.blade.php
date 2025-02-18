<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/css/profile.css">
	<script src="/js/api_requests.js"></script>
	<script src="/js/utils.js"></script>
	<title></title>
</head>
<body>
	@include("navbar")
	<div id="profile">
		<img id="discordpic" src="https://cdn.discordapp.com/avatars/{{$infos->discord_user->discord_id}}/{{$infos->discord_user->avatar_hash}}.png?size=512">
		<h1>{{$infos->player->login}}</h1>
		<h2>{{$infos->discord_user->username}}</h2>

		<div id="recent_scores">
			<table>
				<caption>Recent Runs</caption>
				<thead>
					<tr>
						<th class="date">Date</th>
						<th class="pos">#</th>
						<th class="map">Map Name</th>
						<th class="death">Deaths</th>
						<th class="time">time</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		loadRecentRuns()

		async function loadRecentRuns() {
			let table = document.querySelector("#recent_scores table tbody");
			let scores = await getUserHighscores({{$infos->player->id}});

			for (let score of scores) {
				let row = table.insertRow(-1);

				let dt_c = row.insertCell(-1);
				let pos_c = row.insertCell(-1);
				let map_c = row.insertCell(-1);
				let deaths_c = row.insertCell(-1);
				let time_c = row.insertCell(-1);

				let date = new Date(score.created_at);

				dt_c.innerHTML = `${date.getDate()}/${date.getMonth()}/${date.getFullYear()}`;
				pos_c.innerHTML = score.rank;
				map_c.innerHTML = score.map.name;
				deaths_c.innerHTML = score.death_count;
				time_c.innerHTML = parseMs(score.time);
			}
		}
	</script>
</body>
</html>