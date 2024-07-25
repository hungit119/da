<?php

namespace App\Repositories;

use App\Models\CheckList;

class CheckListRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return CheckList::class;
    }

}
