<?php

namespace App\Repositories;

use App\Models\UserRole;

class UserRoleRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return UserRole::class;
    }

    public function getRoleByUser(int $userID)
    {
        return $this->_model->where(UserRole::_USER_ID, $userID)->whereNull(UserRole::_DELETED_AT)->get();
    }
}
