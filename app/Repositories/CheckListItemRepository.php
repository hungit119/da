<?php

namespace App\Repositories;

use App\Models\CheckListItem;

class CheckListItemRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return CheckListItem::class;
    }
}
