async function getHighscoreByName(name) {
	return new Promise(res => {
		let request = new XMLHttpRequest();

		request.open("GET", "/api/highscores/"+name, false);
		request.onreadystatechange = () => {
			res(request);
		};

		request.send();
	});
}