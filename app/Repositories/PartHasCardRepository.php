<?php

namespace App\Repositories;

use App\Models\PartHasCard;

class PartHasCardRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return PartHasCard::class;
    }
}
