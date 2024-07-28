<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Repositories\UserRepository;
use App\Repositories\UserRoleRepository;
use App\Services\GeneralTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private Request             $request;
    private UserRepository      $userRepo;
    private UserRoleRepository  $userRoleRepo;
    private GeneralTokenService $generalTokenService;

    public function __construct(
        Request $request,
        UserRepository $userRepo,
        UserRoleRepository $userRoleRepo,
        GeneralTokenService $generalTokenService
    ) {
        $this->request             = $request;
        $this->userRepo            = $userRepo;
        $this->userRoleRepo        = $userRoleRepo;
        $this->generalTokenService = $generalTokenService;
    }

    public function login(): JsonResponse
    {
        $this->validateBase($this->request, [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials = $this->request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user          = Auth::user();
            $data          = $this->setPermissions($user);
            $this->message = 'login success';
            $this->status  = 'success';
            return $this->responseData($data);
        }

        $this->message = 'login failed';
        $this->code    = 401;
        return $this->responseData();
    }

    public function register(): JsonResponse
    {
        $this->validateBase($this->request, [
            'name'     => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $name     = $this->request->input('name');
        $email    = $this->request->input('email');
        $password = $this->request->input('password');

        // check user exist
        $user = $this->userRepo->findByEmailAndRole($email, Role::ROLE_LEADER);
        if (isset($user)) {
            $this->message = 'user already exist';
            $this->code    = 400;
            return $this->responseData();
        }

        $newUser = [
            User::_NAME     => $name,
            User::_EMAIL    => $email,
            User::_PASSWORD => bcrypt($password),
        ];
        DB::beginTransaction();
        try {
            $data = $this->userRepo->create($newUser);
            if (!$data) {
                DB::rollBack();
                $this->message = 'register failed : user not created';
                $this->code    = 400;
                return $this->responseData();
            } else {
                $role = $this->userRoleRepo->create([
                    UserRole::_USER_ID => $data->id,
                    UserRole::_ROLE_ID => Role::ROLE_LEADER
                ]);
                if (!$role) {
                    DB::rollBack();
                    $this->message = 'register failed : role not set';
                    $this->code    = 400;
                    return $this->responseData();
                }
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->message = $e->getMessage();
            $this->code    = 400;
            return $this->responseData();
        }
        $this->message = 'register success';
        $this->status  = 'success';
        return $this->responseData($data);
    }

    private function setPermissions($user)
    {
        list($user, $listRole) = $this->getUserRole($user);
        $accessToken = $this->setToken($user->toArray());
        return [
            'user'         => $user,
            'access_token' => $accessToken
        ];
    }

    private function getUserRole($user): array
    {
        $listRole      = $this->userRoleRepo->getRoleByUser($user->id)->keyBy(UserRole::_ROLE_ID)->toArray();
        $user['roles'] = array_keys($listRole);
        $user->toArray();
        return [$user, $listRole];
    }

    private function setToken(array $data)
    {
        $time_during_system = $data->time_during_system ?? 60;
        return $this->generalTokenService->genToken($data, ($time_during_system) * 60 * 24, env('KEY_TOKEN'));
    }

    public function signInWithGoogle()
    {
        $validated = $this->validateBase($this->request, [
            'email' => ['required', 'email'],
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $email     = $this->request->get('email');
        $avatar    = $this->request->get('avatar');
        $name      = $this->request->get('name');
        $givenName = $this->request->get('given_name');

        $user = $this->userRepo->findByEmailAndRole($email, Role::ROLE_LEADER);
        $this->userRepo->update($user[User::_ID],[
           User::_AVATAR => $avatar,
           User::_NAME => $name ." ". $givenName,
        ]);
        $user[User::_AVATAR] = $avatar;
        if (isset($user)) {
            goto next;
        }

        $newUser = [
            User::_EMAIL    => $email,
            User::_NAME     => $name ." ". $givenName,
            User::_PASSWORD => bcrypt("12345678"),
            User::_AVATAR   => $avatar,
        ];
        $user    = $this->userRepo->create($newUser);

        next:
        $data          = $this->setPermissions($user);
        $this->message = 'login success';
        $this->status  = 'success';
        return $this->responseData($data);
    }
}
