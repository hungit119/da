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
}
