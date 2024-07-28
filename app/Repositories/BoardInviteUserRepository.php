<?php

namespace App\Repositories;

use App\Models\BoardInviteUser;

class BoardInviteUserRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return BoardInviteUser::class;
    }
}
