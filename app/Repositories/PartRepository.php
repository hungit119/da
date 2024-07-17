<?php

namespace App\Repositories;

use App\Models\Part;

class PartRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Part::class;
    }

    public function getListPartByBoardID(array $select, mixed $boardId)
    {
        return $this->_model::with(["cards"])->select($select)
            ->where(Part::_BOARD_ID, $boardId)
            ->whereNull(Part::_DELETED_AT)
            ->get();
    }
}
