<?php

namespace App\Repositories;

use App\Models\Role;

class RoleRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Role::class;
    }
}
