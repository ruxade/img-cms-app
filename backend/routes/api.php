<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;


// Public routes.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected route. This uses the alias "auth" combined with Sanctum's token guard.
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
