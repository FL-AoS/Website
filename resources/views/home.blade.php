<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/home.css">

	<script src="/js/api_requests.js"></script>
	<title>a</title>
</head>
<body>
	@include("navbar")
	<div id="current_highscore">
		@include("animation")
		<table>
			<caption>Current Map</caption>
			<thead>
				<tr>
					<th class="pos">#</th>
					<th class="name">Name</th>
					<th class="time">Time</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>

	<script type="text/javascript">
		loadCurrentHighscore();

		function parseMs(ms) {
			let minutes = Math.floor(ms/1000/60).toString();
			let seconds = Math.floor(ms/1000%60).toString();
			let ms_t = Math.floor(ms%1000).toString();

			if (minutes.length == 1) {
				minutes = minutes.split("");
				minutes.unshift("0");
				minutes = minutes.join("");
			}


			if (seconds.length == 1) {
				seconds = seconds.split("");
				seconds.unshift("0");
				seconds = seconds.join("");
			}

			if (ms_t.length < 3) {
				let diff = 3-ms_t.length;
				for (let i = 0; i < diff; i++) {
					ms_t = ms_t.split("");
					ms_t.unshift("0");
					ms_t = ms_t.join("");
				}
			}


			return `${minutes}:${seconds}:${ms_t}`
		}

		function loadCurrentHighscore() {
			let tableElement = document.querySelector("#current_highscore table");
			let captionElement = document.querySelector("#current_highscore table caption");
			let currentHighscoreElement = document.querySelector("#current_highscore table tbody");
			let loadingAnimationElement = document.getElementById("loading_animation");
			let masterlistRequest = new XMLHttpRequest();

			loadingAnimationElement.style.display = "";
			tableElement.style.display = "none";

			masterlistRequest.open("GET", "http://services.buildandshoot.com/serverlist.json", true);
			masterlistRequest.onreadystatechange = async () => {
				if (masterlistRequest.readyState === XMLHttpRequest.DONE && masterlistRequest.status == 200) {
					let servers = JSON.parse(masterlistRequest.responseText);

					let prkIndex = servers.findIndex(e => e.identifier == "aos://1931556250:32880");

					if (prkIndex < 0)
						return;

					let highscoreRequest = await getHighscoreByName(servers[prkIndex].map);
					let highscoreObj = JSON.parse(highscoreRequest.responseText);

					let index, entry;
					for ([index, entry] of highscoreObj.entries()) {
						let row = currentHighscoreElement.insertRow(-1);
						let pos = row.insertCell(-1);
						let name = row.insertCell(-1);
						let time = row.insertCell(-1);

						pos.innerHTML = index+1;
						name.innerHTML = entry.player.login;
						time.innerHTML = parseMs(entry.run);
					}

					if (index < 15) {
						for (; index < 15; index++) {
							let row = currentHighscoreElement.insertRow(-1);
							row.insertCell(-1).innerHTML = "--";
							row.insertCell(-1).innerHTML = "------------";
							row.insertCell(-1).innerHTML = "-- : -- : ---";
						}
					}

					captionElement.innerHTML = "Current Map: "+servers[prkIndex].map;
					loadingAnimationElement.style.display = "none";
					tableElement.style.display = "";
				}
			};

			masterlistRequest.send();
		}
	</script>
</body>
</html>