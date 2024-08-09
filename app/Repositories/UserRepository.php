<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;

class UserRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return User::class;
    }

    public function findByEmailAndRole(mixed $email, string $role)
    {
        return $this->_model::where(User::_EMAIL, $email)->whereHas('roles', function ($query) use ($role) {
            $query->where(Role::_ID, $role);
        })->first();
    }

    public function findByEmail(mixed $emailReceiver)
    {
        return $this->_model->where(User::_EMAIL, $emailReceiver)
            ->whereNull(User::_DELETED_AT)
            ->first();
    }

    public function findByID(mixed $userID)
    {
        return $this->_model->where(User::_ID, $userID)->whereNull(User::_DELETED_AT)->first();
    }

    public function getUsers(mixed $perPage,mixed $keyword)
    {
        $query = $this->_model::with("roles")
            ->whereNull(User::_DELETED_AT);

        if ($keyword){
            $query->where(function ($query) use ($keyword) {
                $query->where(User::_NAME, 'like', '%' . $keyword . '%')
                    ->orWhere(User::_EMAIL, 'like', '%' . $keyword . '%');
            });
        }
        return $query->paginate($perPage);
    }

    public function getByEmail(mixed $email)
    {
        return $this->_model->where(User::_EMAIL,$email)->whereNull(User::_DELETED_AT)->first();
    }

    public function getUser(mixed $id)
    {
        return $this->_model::with("roles")->where(User::_ID,$id)->first();
    }
}
