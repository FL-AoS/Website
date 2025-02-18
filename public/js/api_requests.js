async function getHighscoreByMapName(name) {
	return new Promise(res => {
		let request = new XMLHttpRequest();

		request.open("GET", "/api/highscores/"+name, false);
		request.onreadystatechange = () => {
			res(JSON.parse(request.responseText));
		};

		request.send();
	});
}

async function getUserInfosByPlayerName(name) {
	return new Promise(res => {
		let request = new XMLHttpRequest();

		request.open("GET", "/api/user/player/name/"+name, false);
		request.onreadystatechange = () => {
			try {
				res(JSON.parse(request.responseText));
			} catch {
				res(null);
			}
		};

		request.send();
	});
}


async function getUserHighscores(player_id) {
	return new Promise(res => {
		let request = new XMLHttpRequest();

		request.open("GET", "/api/highscores/player/"+player_id, false);
		request.onreadystatechange = () => {
			try {
				res(JSON.parse(request.responseText));
			} catch {
				res(null);
			}
		};

		request.send();
	});
}