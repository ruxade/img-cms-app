<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\ImageController;


// Public routes.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected route. This uses the alias "auth" combined with Sanctum's token guard.
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Protected Image Management Routes
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/public-images', [ImageController::class, 'publicIndex']);
  Route::get('/images/{id}', [ImageController::class, 'show']);
  Route::get('/images', [ImageController::class, 'index']);
  Route::post('/images', [ImageController::class, 'store']);
  Route::middleware('auth:sanctum')->patch('/images/{id}/toggle-visibility', [ImageController::class, 'toggleVisibility']);

  Route::delete('/images/{id}', [ImageController::class, 'destroy']);
});
