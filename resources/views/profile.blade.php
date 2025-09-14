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
	@include("user_config")
	<div id="profile">
		<img id="discordpic" src="https://cdn.discordapp.com/avatars/{{$infos->discord_user->discord_id}}/{{$infos->discord_user->avatar_hash}}.png?size=512">
		@if(!is_null($infos->player))
		<h1>{{$infos->player->login}}</h1>
		@else
		<h1>NOT REGISTERED</h1>
		@endif
		<h2>{{$infos->discord_user->username}}</h2>
		<div id="roles">
			@foreach ($infos->roles as $role)
				<div class="role_card" style="background-color: #{{dechex($role->color)}};"><p>{{$role->display_name}}</p></div>
			@endforeach
		</div>

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

		@auth
		@if(auth()->user()->id == $infos->id)
		<svg id="config_icon" onclick="toggleConfigMenu()" width="100px" height="100px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M23.265,24.381l.9-.894c4.164.136,4.228-.01,4.411-.438l1.144-2.785L29.805,20l-.093-.231c-.049-.122-.2-.486-2.8-2.965V15.5c3-2.89,2.936-3.038,2.765-3.461L28.538,9.225c-.171-.422-.236-.587-4.37-.474l-.9-.93a20.166,20.166,0,0,0-.141-4.106l-.116-.263-2.974-1.3c-.438-.2-.592-.272-3.4,2.786l-1.262-.019c-2.891-3.086-3.028-3.03-3.461-2.855L9.149,3.182c-.433.175-.586.237-.418,4.437l-.893.89c-4.162-.136-4.226.012-4.407.438L2.285,11.733,2.195,12l.094.232c.049.12.194.48,2.8,2.962l0,1.3c-3,2.89-2.935,3.038-2.763,3.462l1.138,2.817c.174.431.236.584,4.369.476l.9.935a20.243,20.243,0,0,0,.137,4.1l.116.265,2.993,1.308c.435.182.586.247,3.386-2.8l1.262.016c2.895,3.09,3.043,3.03,3.466,2.859l2.759-1.115C23.288,28.644,23.44,28.583,23.265,24.381ZM11.407,17.857a4.957,4.957,0,1,1,6.488,2.824A5.014,5.014,0,0,1,11.407,17.857Z"/></svg>

		@endauth
		@endif
	</div>

	<script type="text/javascript">

		@if(!is_null($infos->player))
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
		@else
		toggleConfigMenu()
		@endif

		function toggleConfigMenu() {
			let cfgelem = document.getElementById("config_menu");

			console.log(!cfgelem.style.display);
			cfgelem.style.display = (!cfgelem.style.display) ? "block" : "";
		}

		@if(!is_null($infos->player) && $errors->any())
			toggleConfigMenu()
		@endif
	</script>
</body>
</html>