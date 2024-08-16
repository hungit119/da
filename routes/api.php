<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\BoardHasUserController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PartHasCardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckListController;
use App\Http\Controllers\CheckListItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardInviteUserController;
use App\Http\Controllers\ActivityController;
use App\Http\Middleware\VerifyTokenApp;

// for user
Route::post('/login', [AuthController::class, 'login']);
Route::post('/sign-in-with-google',[AuthController::class, 'signInWithGoogle']);
Route::post('/pre-sign-in',[AuthController::class, 'preSignIn']);

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

    Route::post("/invite-user-to-board", [BoardController::class, 'inviteUserToBoard']);
    Route::post("/update-invite-guest", [BoardInviteUserController::class,"updateInviteQuest"]);
    Route::post("/edit-board-user",[BoardHasUserController::class,'editBoardHasUser']);

    // part
    Route::post("/create-part", [PartController::class, 'create']);
    Route::get("/get-list-part", [PartController::class, 'list']);
    Route::post("/update-position-parts",[PartController::class,'updatePosition']);
    Route::post("/update-part",[PartController::class,'updatePart']);

    // card
    Route::get("/get-list-card",[CardController::class, 'list']);
    Route::post("/create-card",[CardController::class, 'create']);
    Route::post("/update-part-card",[PartHasCardController::class,'updatePartCard']);
    Route::post("/save-card",[CardController::class,'saveCard']);
    Route::post("/update-card",[CardController::class,'updateCard']);

    // checklist
    Route::post("/create-checklist",[CheckListController::class,'create']);
    Route::post("/update-checklist",[CheckListController::class,'update']);

    // checklist item
    Route::post("/create-checklist-item",[CheckListItemController::class,'create']);
    Route::post("/update-checklist-item",[CheckListItemController::class,'update']);

    // boardHasUser
    Route::post("/update-board-has-user",[BoardHasUserController::class,'updateBoardHasUser']);
    Route::post("/accept_invite_board",[BoardHasUserController::class,'acceptInviteBoard']);

    // board invite user
    Route::get("/get-list-board-invite-user",[BoardInviteUserController::class,'list']);

    // comment
    Route::post("/create-comment",[ActivityController::class,"createComment"]);
    Route::get("/get-list-comment",[ActivityController::class,"getListComment"]);

    // user
    Route::get("/get-users",[UserController::class,"getListUser"]);
    Route::post("/update-user",[UserController::class,"updateUser"]);
    Route::post("/delete-user",[UserController::class,"deleteUser"]);
    Route::post("/create-user",[UserController::class,"createUser"]);
    Route::get("/get-user",[UserController::class,"getUser"]);

    Route::post("/predict-time-end",[CheckListItemController::class,"predictTimeEnd"]);
});
