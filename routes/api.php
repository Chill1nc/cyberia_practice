<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [CatalogController::class, 'books']);
Route::get('/genres', [CatalogController::class, 'genres']);
Route::get('/authors', [CatalogController::class, 'authors']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/verify', [AuthController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
