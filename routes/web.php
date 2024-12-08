<?php

use App\Http\Controllers\HighscoreController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::prefix('/api')->group(function () {
    Route::get('/highscores/{map_name}', [HighscoreController::class, 'getScoreByMapName']);
});
