<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\HighscoreController;

Route::get('/', function () {
    return view("home");
});

Route::prefix("/api")->group(function() {
    Route::get("/highscores/{map_name}", [HighscoreController::class, "getScoreByMapName"]);
});