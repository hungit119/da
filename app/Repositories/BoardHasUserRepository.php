<?php

namespace App\Repositories;

use App\Models\BoardHasUser;

class BoardHasUserRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return BoardHasUser::class;
    }
}
