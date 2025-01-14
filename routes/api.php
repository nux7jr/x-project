<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaderboardController;


// Создает нового пользователя с уникальным именем.
Route::post('/users',  [UserController::class, 'create']);

// Добавляет очки пользователю.
Route::post('/users/{userId}/score', [LeaderboardController::class, 'store']);

// Возвращает топ-10 пользовательского рейтинга.
Route::get('/leaderboard/top', [LeaderboardController::class, 'index']);

// Возвращает место пользователя в рейтинге.
Route::get('/leaderboard/rank/{userId}', [LeaderboardController::class, 'show']);
