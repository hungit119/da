<?php

namespace App\Http\Controllers;

use App\Models\BoardHasUser;
use App\Models\BoardInviteUser;
use App\Repositories\BoardHasUserRepository;
use App\Repositories\BoardInviteUserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoardInviteUserController extends Controller
{
    private Request                   $request;
    private BoardInviteUserRepository $boardInviteUserRepo;
    private BoardHasUserRepository     $boardHasUserRepo;

    public function __construct(
        Request $request,
        BoardInviteUserRepository $boardInviteUserRepo,
        BoardHasUserRepository $boardHasUserRepo
    ) {
        $this->request             = $request;
        $this->boardInviteUserRepo = $boardInviteUserRepo;
        $this->boardHasUserRepo    = $boardHasUserRepo;
    }

    public function list()
    {
        $validated = $this->validateBase($this->request, [
            'board_id' => 'required'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $boardID = $this->request->get('board_id');

        $list = $this->boardInviteUserRepo->getByBoardID($boardID)->toArray();

        $this->status  = "success";
        $this->message = "get list request successfully";
        return $this->responseData($list);
    }

    public function updateInviteQuest(){
        $validated = $this->validateBase($this->request, [
            'board_id' => 'required',
            'user_id' => 'required',
            'email' => 'required',
            'role_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $userID = $this->request->get('user_id');
        $boardID = $this->request->get('board_id');
        $email = $this->request->get('email');
        $roleID = $this->request->get('role_id');

        $this->boardInviteUserRepo->updateByEmailBoardID($email,$boardID,[
            BoardInviteUser::_DELETED_AT => time(),
        ]);

        $boardHasUser = $this->boardHasUserRepo->create([
            BoardHasUser::_USER_ID => $userID,
            BoardHasUser::_BOARD_ID => $boardID,
            BoardHasUser::_ROLE_ID => $roleID,
            BoardHasUser::_STATUS_ACCEPT => BoardHasUser::STATUS_ACCEPTED,
        ]);

        $this->status  = "success";
        $this->message = "update request successfully";
        return $this->responseData($boardHasUser);
    }
}
