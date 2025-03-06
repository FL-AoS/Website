<?php

use App\Http\Controllers\HighscoreController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\RunController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

Route::get('/leaderboard', [LeaderboardController::class, 'handleLeaderboard']);

Route::get('/profile/{id}', [UserController::class, 'handleProfile']);

Route::prefix('/api')->group(function () {
    Route::get('/user/{id}', [UserController::class, 'getUserInfosById']);
    Route::get('/user/player/{player_id}', [UserController::class, 'getUserInfosByPlayerId']); // this is ugly, i need to think in a better structure
    Route::get('/user/player/name/{player_name}', [UserController::class, 'getUserInfosByPlayerName']);

    Route::get('/highscores/{map_name}', [HighscoreController::class, 'getScoreByMapName']);
    Route::get('/highscores/player/{player_name}', [HighscoreController::class, 'getPlayerScoresByPlayerId']);

    Route::get('/run/{run_id}', [RunController::class, 'getRunInfo']);

    Route::get('/leaderboard/categories', [LeaderboardController::class, 'getLeaderboardCategories']);

    Route::get('/discord/authorization', [DiscordController::class, 'authorize']);

    Route::post('/update_login', [PlayerController::class, 'update_infos']);
});