<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\GenreController;

Route::apiResource('movies', MovieController::class);

Route::get('genres', [GenreController::class, 'index']);
Route::get('genres/{id}', [GenreController::class, 'show']);