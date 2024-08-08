<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardHasUser;
use App\Models\BoardInviteUser;
use App\Models\Role;
use App\Models\User;
use App\Repositories\BoardHasUserRepository;
use App\Repositories\BoardInviteUserRepository;
use App\Repositories\BoardRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BoardController extends Controller
{
    private Request                   $request;
    private BoardRepository           $boardRepo;
    private BoardHasUserRepository    $boardHasUserRepo;
    private UserRepository            $userRepo;
    private BoardInviteUserRepository $boardInviteUserRepo;

    public function __construct(
        Request $request,
        BoardRepository $boardRepo,
        BoardHasUserRepository $boardHasUserRepo,
        UserRepository $userRepo,
        BoardInviteUserRepository $boardInviteUserRepo
    ) {
        $this->request             = $request;
        $this->boardRepo           = $boardRepo;
        $this->boardHasUserRepo    = $boardHasUserRepo;
        $this->userRepo            = $userRepo;
        $this->boardInviteUserRepo = $boardInviteUserRepo;
    }

    public function create()
    {
        $validatedData = $this->validateBase($this->request, [
            'name'    => 'required',
            'type'    => 'required',
            'user_id' => 'required'
        ]);

        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $name   = $this->request->input('name');
        $type   = $this->request->input('type');
        $avatar = $this->request->input('avatar');
        $userId = $this->request->input('user_id');

        $data = [
            Board::_NAME   => $name,
            Board::_TYPE   => $type,
            Board::_AVATAR => $avatar
        ];
        DB::beginTransaction();
        try {
            $result = $this->boardRepo->create($data);
            if (isset($result)) {
                $data        = [
                    BoardHasUser::_USER_ID  => $userId,
                    BoardHasUser::_BOARD_ID => $result[Board::_ID],
                    BoardHasUser::_ROLE_ID => Role::ROLE_ADMIN
                ];
                $resultPilot = $this->boardHasUserRepo->create($data);
                if (!isset($resultPilot)) {
                    DB::rollBack();
                    $this->code    = 400;
                    $this->message = 'Gắn bảng vào user thất bại';
                    return $this->responseData();
                }
                DB::commit();
                $this->message = 'Tạo bảng thành công';
                $this->status  = 'success';
                return $this->responseData($result);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->code    = 400;
            $this->message = $e->getMessage();
            return $this->responseData($e);
        }
    }

    public function list()
    {
        $validatedData = $this->validateBase($this->request, [
            'user_id' => 'required'
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $userId = $this->request->input('user_id');
        $select = [
            Board::TABLE . '.' . Board::_ID,
            Board::TABLE . '.' . Board::_AVATAR,
            Board::TABLE . '.' . Board::_NAME,
        ];

        $listBoard    = $this->boardRepo->getListBoardByUserID($select, $userId)->toArray();
        $this->status = "success";
        return $this->responseData($listBoard);
    }

    public function delete()
    {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }
        $boardId = $this->request->input('board_id');
        $result  = $this->boardRepo->update($boardId, [
            Board::_DELETED_AT => time()
        ]);
        if (isset($result)) {
            $this->code = 200;
            return $this->responseData($result);
        }
        $this->code    = 400;
        $this->message = 'delete fail';
        return $this->responseData($result);
    }

    public function update()
    {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $boardId = $this->request->input('board_id');
        $name    = $this->request->input('name');
        $type    = $this->request->input('type');
        $avatar  = $this->request->input('avatar');

        $dataUpdate = [];
        if (isset($boardId)) {
            $dataUpdate[Board::_NAME] = $name;
        }
        if (isset($type)) {
            $dataUpdate[Board::_TYPE] = $type;
        }
        if (isset($avatar)) {
            $dataUpdate[Board::_AVATAR] = $avatar;
        }
        $result = $this->boardRepo->update($boardId, $dataUpdate);
        if (isset($result)) {
            $this->code    = 200;
            $this->status  = "success";
            $this->message = "Update board success";
            return $this->responseData($result);
        }
        $this->code    = 400;
        $this->message = 'update fail';
        return $this->responseData($result);
    }

    public function get()
    {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }
        $boardId = $this->request->input('board_id');
        $result  = $this->boardRepo->getBoardDetail($boardId);
        if (isset($result)) {
            $this->code = 200;
            return $this->responseData($result);
        }
        $this->code    = 400;
        $this->message = 'get fail';
        return $this->responseData($result);
    }

    public function inviteUserToBoard()
    {
        $validatedData = $this->validateBase($this->request, [
            'email_receiver'    => 'required | email',
            'board_id'          => 'required',
            'board_name'        => 'required | string',
            'role_id'           => 'required',
            'user_invite_name'  => 'required | string',
            'user_invite_email' => 'required'
        ]);

        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $emailReceiver   = $this->request->input('email_receiver');
        $boardId         = $this->request->input('board_id');
        $boardName       = $this->request->input('board_name');
        $roleId          = $this->request->input('role_id');
        $userInviteName  = $this->request->input('user_invite_name');
        $userInviteEmail = $this->request->input('user_invite_email');

        $user = $this->userRepo->findByEmail($emailReceiver);
        if (isset($user)) {
            $boardHasUser = $this->boardHasUserRepo->findByUserIDAndBoardID($user[User::_ID], $boardId);
            if (isset($boardHasUser)) {
                $this->code    = 400;
                $this->message = "Đã gửi yêu cầu cho người dùng này";
                return $this->responseData();
            } else {
                // send email to user
                Mail::to($user[User::_EMAIL])->send(new \App\Mail\UserInvitationJoinBoardEmail(
                    $userInviteName,
                    $userInviteEmail,
                    $boardName,
                    $boardId,
                    $user[User::_EMAIL]
                ));
                $newBoardHasUser = $this->boardHasUserRepo->create([
                    BoardHasUser::_USER_ID       => $user[User::_ID],
                    BoardHasUser::_BOARD_ID      => $boardId,
                    BoardHasUser::_STATUS_ACCEPT => 0,
                    BoardHasUser::_ROLE_ID       => $roleId,
                ]);

                $this->code    = 200;
                $this->status  = "success";
                $this->message = "Mời người dùng trong hệ thống thành công";
                return $this->responseData($newBoardHasUser);
            }
        }
        $requestQuest = $this->boardInviteUserRepo->findByEmailAndBoardID($userInviteEmail,$boardId);
        if (isset($requestQuest)){
            $this->code    = 400;
            $this->message = "Đã gửi yêu cầu cho người dùng này";
            return $this->responseData();
        }
        $newBoardInViteUser = $this->boardInviteUserRepo->create([
            BoardInviteUser::_BOARD_ID      => $boardId,
            BoardInviteUser::_EMAIL_INVITED => $emailReceiver,
            BoardInviteUser::_ROLE_ID       => $roleId
        ]);
        Mail::to($emailReceiver)->send(new \App\Mail\UserDontExistInvitationJoinBoardEmail(
            $userInviteName,
            $userInviteEmail,
            $boardName,
            $boardId,
            $newBoardInViteUser[BoardInviteUser::_ID],
            $emailReceiver,
            $roleId
        ));

        $this->code    = 200;
        $this->status  = "success";
        $this->message = "Mời người dùng ngoài hệ thống thành công";
        return $this->responseData($newBoardInViteUser);
    }
}
