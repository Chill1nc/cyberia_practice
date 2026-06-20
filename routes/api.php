<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [CatalogController::class, 'books']);
Route::get('/books/filters', [CatalogController::class, 'filters']);
Route::get('/books/{book}', [CatalogController::class, 'show']);
Route::get('/genres', [CatalogController::class, 'genres']);
Route::get('/authors', [CatalogController::class, 'authors']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/verify', [AuthController::class, 'verify']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{book}', [FavoriteController::class, 'destroy']);
    Route::delete('/favorites', [FavoriteController::class, 'clear']);
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store']);
    Route::post('/reviews/{review}/rate', [\App\Http\Controllers\ReviewController::class, 'rate']);
});
