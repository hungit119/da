<?php

namespace App\Repositories;

use App\Models\Attachment;
use App\Models\Card;
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
        return $this->_model::with(["cards" => function ($query) {
            $query->orderBy(Card::_POSITION);
        }, "cards.attachments" => function ($query) {
            $query->orderBy(Attachment::_TYPE);
        }, "cards.checklists.checkListItems"])->select($select)
            ->where(Part::_BOARD_ID, $boardId)
            ->whereNull(Part::_DELETED_AT)
            ->orderBy(Part::_POSITION)
            ->get();
    }
}
