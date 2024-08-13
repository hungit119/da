<?php

namespace App\Http\Controllers;

use App\Models\Socket;
use App\Models\User;
use App\Models\UserRole;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use App\Services\WebSocketService;
use Illuminate\Http\Request;
use function Symfony\Component\Translation\t;

class UserController extends Controller
{
    private Request $request;
    private WebSocketService $socketService;
    private UserRepository $userRepo;
    private UserRoleRepository $userRoleRepo;
    public function __construct(
        Request $request,
        WebSocketService $socketService,
        UserRepository $userRepo,
        UserRoleRepository $userRoleRepo
    )
    {
        $this->request = $request;
        $this->socketService = $socketService;
        $this->userRepo = $userRepo;
        $this->userRoleRepo = $userRoleRepo;
    }

    public function sendNoti() {

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

        $message = [
            'type'      => WebSocketService::TREELO_WEB_MEMBER,
            'service'   => WebSocketService::SERVICE,
            'condition' => [
                'user_id' => $userID,
                'board_id' => $boardID
            ],
            'data'      => [
                'action'       => Socket::ACTION_USER_ACCEPT_JOIN_BOARD,
                'board_id'      => $boardID,
            ]
        ];
        $this->socketService->sendMessage($message);

        $this->status = 'success';
        $this->message = "send noti success";
        return $this->responseData();
    }

    public function createUser() {
        $validated = $this->validateBase($this->request,[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $roleID = $this->request->get('role_id');

        $check = $this->userRepo->getByEmail($email);
        if ($check){
            $this->code = 400;
            $this->message = "User existed";
            return $this->responseData($check);
        }
        $user = $this->userRepo->create([
            User::_NAME => $name,
            User::_EMAIL => $email,
            User::_PASSWORD => $password,
        ]);

        if (isset($user)){
            $this->userRoleRepo->create([
                UserRole::_USER_ID => $user[User::_ID],
                UserRole::_ROLE_ID => $roleID,
            ]);
        }
        $this->code = 200;
        $this->message = "create user success";
        return $this->responseData();
    }
    public function updateUser() {
        $validated = $this->validateBase($this->request,[
            'id' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $id = $this->request->get('id');
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $avatar = $this->request->get('avatar');
        $roleID = $this->request->get('role_id');

        $dataUpdate = [];
        if (isset($name)){
            $dataUpdate['name'] = $name;
        }
        if (isset($email)){
            $dataUpdate['email'] = $email;
        }
        if (isset($password)){
            $dataUpdate['password'] = $password;
        }
        if (isset($avatar)){
            $dataUpdate['avatar'] = $avatar;
        }
        if (isset($roleID)){
            $this->userRoleRepo->updateByUserID($id,$roleID);
        }
        $this->userRepo->update($id,$dataUpdate);
        $this->code = 200;
        $this->message = "update user success";
        return $this->responseData();
    }
    public function getListUser()
    {
        $page = $this->request->get('page', 1);
        $perPage = $this->request->get('perPage', 10);
        $keyword = $this->request->get('keyword');
        $users = $this->userRepo->getUsers($perPage,$keyword)->toArray();
        $this->code = 200;
        $this->message = "get user success";
        return $this->responseData($users);
    }
    public function deleteUser()
    {
        $id = $this->request->get('id');
        $this->userRepo->update($id,[
            User::_DELETED_AT => time()
        ]);
        $this->message = "delete user success";
        $this->status="success";
        return $this->responseData();
    }
    public function getUser()
    {
        $id = $this->request->get('id');
        $user = $this->userRepo->getUser($id);
        $this->message = "get user success";
        $this->status="success";
        return $this->responseData($user);
    }
}
