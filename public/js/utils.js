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

function promiseRequest(url) {
	return new Promise(res => {
		let request = new XMLHttpRequest();

		request.open("GET", url, false);
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