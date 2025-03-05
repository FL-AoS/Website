async function getHighscoreByMapName(name) {
	return await promiseRequest("/api/highscores/"+name);
}

async function getUserInfosByPlayerName(name) {
	return await promiseRequest("/api/user/player/name/"+name);
}

async function getUserHighscores(player_id) {
	return await promiseRequest("/api/highscores/player/"+player_id);
}

async function getLeaderboardCategories() {
	return await promiseRequest("/api/leaderboard/categories");
}