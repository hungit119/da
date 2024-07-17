<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyTokenApp;

// for user
Route::post('/login', [AuthController::class, 'login']);

// for admin
Route::post('/register', [AuthController::class, 'register']);

// route

Route::prefix('v1')->middleware([VerifyTokenApp::class])->group(function () {
    // board
    Route::post("/create-board", [BoardController::class, 'create']);
    Route::get("/get-list-board", [BoardController::class, 'list']);
    Route::delete("/delete-board", [BoardController::class, 'delete']);
    Route::put("/update-board", [BoardController::class, 'update']);
    Route::get("/detail-board", [BoardController::class, 'get']);

    // part
    Route::post("/create-part", [PartController::class, 'create']);
    Route::get("/get-list-part", [PartController::class, 'list']);

    // card
    Route::get("/get-list-card",[CardController::class, 'list']);
    Route::post("/create-card",[CardController::class, 'create']);
});
