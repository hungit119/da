<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PartHasCardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckListController;
use App\Http\Controllers\CheckListItemController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\VerifyTokenApp;

// for user
Route::post('/login', [AuthController::class, 'login']);

// for admin
Route::post('/register', [AuthController::class, 'register']);

// user
Route::get('/accept-invitation',[UserController::class,'sendNoti']);

// authenticated
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
    Route::post("/update-position-parts",[PartController::class,'updatePosition']);

    // card
    Route::get("/get-list-card",[CardController::class, 'list']);
    Route::post("/create-card",[CardController::class, 'create']);
    Route::post("/update-part-card",[PartHasCardController::class,'updatePartCard']);
    Route::post("/save-card",[CardController::class,'saveCard']);

    // checklist
    Route::post("/create-checklist",[CheckListController::class,'create']);

    // checklist item
    Route::post("/create-checklist-item",[CheckListItemController::class,'create']);
    Route::post("/update-checklist-item",[CheckListItemController::class,'update']);
});
