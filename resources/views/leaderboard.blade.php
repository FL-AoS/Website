<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

	<link rel="stylesheet" type="text/css" href="/css/leaderboard.css">
	<link rel="stylesheet" type="text/css" href="/css/highscore.css">

	<script src="/js/api_requests.js"></script>
	<script src="/js/utils.js"></script>
</head>
<body>
	@include("navbar")
	<form id="categories">
		@csrf
		<label for="gamemode">GameMode</label>
		<label for="cat_1"></label>
		<label for="cat_2"></label>
		<label for="cat_3"></label>
		<select id="gamemodes" name="gamemode" onchange="updateSubCategories()"></select>
		<select id="cat_1" name="cat_1" onchange="handleChange()"></select>
		<select id="cat_2" name="cat_2" onchange="handleChange()"></select>
		<select id="cat_3" name="cat_3" onchange="handleChange()"></select>
	</form>

	<div id="current_highscore">
		<table>
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
		let formElement = document.getElementById("categories");
		let gmSelectElement = document.getElementById("gamemodes");
		let highscoreElem = document.querySelector("#current_highscore table tbody");
		let categories;

		function cleanLeaderboard() {
			highscoreElem.innerHTML = "";
		}

		async function updateLeaderboard(scores) {
			cleanLeaderboard();

			let index, entry;
			for ([index, entry] of scores.entries()) {
				let row = highscoreElem.insertRow(-1);
				let pos = row.insertCell(-1);
				let name = row.insertCell(-1);
				let time = row.insertCell(-1);

				let userInfos = entry.user;

				if (typeof userInfos == "object" && userInfos !== null) {
					row.className = "clickable";
					row.onclick = ()=>{window.location.href = "/profile/"+userInfos.id};
				}

				pos.innerHTML = index+1;
				name.innerHTML = entry.player.login;
				time.innerHTML = parseMs(entry.run);
			}
		}

		async function handleChange() {
			// maybe moving this to the backend?
			if (gmSelectElement.value == "Parkour") {
				let mapsElem = document.getElementById("cat_1");
				updateLeaderboard(await getHighscoreByMapName(mapsElem.value));
			}
		}

		function updateSubCategories() {
			cleanSubCategories();

			let selectedCategory = gmSelectElement.value;

			let sub_cat_i = 1;
			for (let sub_cat of Object.keys(categories[selectedCategory])) {
				let subCatElem = document.getElementById("cat_"+sub_cat_i);
				let labelElem = document.querySelector("label[for=cat_" + sub_cat_i + "]");

				labelElem.innerHTML = sub_cat;

				for (let sub_opt of categories[selectedCategory][sub_cat]) {
					let subopt = document.createElement("option")
					subopt.value = sub_opt;
					subopt.innerHTML = sub_opt;

					subCatElem.appendChild(subopt);
				}

				sub_cat_i += 1;
			}
		}

		function cleanSubCategories() {
			for (let i = 1; i <= 3; i++) {
				let subCatElem = document.getElementById("cat_"+i);
				let labelElem = document.querySelector("label[for=cat_" + i + "]");

				subCatElem.innerHTML = "";
				labelElem.innerHTML = "";
			}
		}

		async function loadCategories() {
			categories = await getLeaderboardCategories();

			for (let gamemode of Object.keys(categories)) {
				let opt = document.createElement("option");
				opt.value = gamemode;
				opt.innerHTML = gamemode;

				gmSelectElement.appendChild(opt);				
			}

			updateSubCategories();
			handleChange();
		}

		loadCategories();
	</script>
</body>
</html>