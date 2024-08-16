<?php

namespace App\Http\Controllers;

use App\Models\BoardHasUser;
use App\Repositories\BoardHasUserRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class BoardHasUserController extends Controller
{
    private Request $request;
    private BoardHasUserRepository $boardHasUserRepo;
    private UserRepository $userRepo;

    public function __construct(
        Request $request,
        BoardHasUserRepository $boardHasUserRepo,
        UserRepository $userRepo
    )
    {
        $this->request = $request;
        $this->boardHasUserRepo = $boardHasUserRepo;
        $this->userRepo = $userRepo;
    }

    public function updateBoardHasUser () {
        $validated = $this->validateBase($this->request,[
            'user_id' => 'required|integer',
            'board_id' => 'required|integer'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $userID = $this->request->get('user_id');
        $boardID = $this->request->get('board_id');
        $statusAccept = $this->request->get('status_accept');

        $dataUpdate = [];

        if ($statusAccept) {
            $dataUpdate[BoardHasUser::_STATUS_ACCEPT] = $statusAccept;
        }
        $this->boardHasUserRepo->updateByUserIDAndBoardID($userID,$boardID,$dataUpdate);

        $this->status = "success";
        $this->message = "Update status accept success";
        return $this->responseData();
    }

    public function editBoardHasUser () {
        $validated = $this->validateBase($this->request,[
            'user_id' => 'required',
            'board_id' => 'required'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $userID = $this->request->get('user_id');
        $boardID = $this->request->get('board_id');
        $roleID= $this->request->get('role_id');
        $isDeleted = $this->request->get('is_deleted');

        $dataUpdate = [];
        if ($roleID) {
            $dataUpdate[BoardHasUser::_ROLE_ID] = $roleID;
        }

        if ($isDeleted) {
            $dataUpdate[BoardHasUser::_DELETED_AT] = time();
        }

        $this->boardHasUserRepo->updateByUserIDAndBoardID($userID,$boardID,$dataUpdate);
        $this->status = "success";
        $this->message = "Update status accept success";
        return $this->responseData();
    }

    public function acceptInviteBoard () {
        $validated = $this->validateBase($this->request,[
            'user_id' => 'required|integer',
            'board_id' => 'required|integer'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $userID = $this->request->get('user_id');
        $boardID = $this->request->get('board_id');

        $user = $this->userRepo->findByID($userID);
        if (isset($user)){
            $message = [];
        }
    }
}
