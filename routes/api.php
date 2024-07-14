<?php

use App\Http\Controllers\BoardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyTokenApp;

// for user
Route::post('/login', [AuthController::class, 'login']);

// for admin
Route::post('/register', [AuthController::class, 'register']);

// route

Route::prefix('v1')->middleware([VerifyTokenApp::class])->group(function () {
    Route::post("/create-board", [BoardController::class, 'create']);
    Route::get("/get-list-board", [BoardController::class, 'list']);
    Route::delete("/delete-board", [BoardController::class, 'delete']);
    Route::put("/update-board", [BoardController::class, 'update']);
    Route::get("/detail-board", [BoardController::class, 'get']);
});
