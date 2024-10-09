<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ScoreboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.provider');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('auth.callback');
Route::get('/game', [GameController::class, 'index'])->name('game.index');
Route::post('/game/play', [GameController::class, 'play'])->name('game.play');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/scoreboard', [ScoreboardController::class, 'index'])->name('scoreboard.index');

