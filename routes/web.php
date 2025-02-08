<?php

use App\Http\Controllers\HighscoreController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
});

Route::get('/user_config', function () {
    if (Auth::check())
        return view('user_config');

    return redirect("/");
});

Route::prefix('/api')->group(function () {
    Route::get('/user/{id}', [UserController::class, 'getUserInfosById']);

    Route::get('/highscores/{map_name}', [HighscoreController::class, 'getScoreByMapName']);

    Route::get('/discord/authorization', [DiscordController::class, 'authorize']);

    Route::post('/update_login', [PlayerController::class, 'update_infos']);
});