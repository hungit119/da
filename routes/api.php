<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// for user
Route::post('/login', [AuthController::class, 'login']);

// for admin
Route::post('/register', [AuthController::class, 'register']);
