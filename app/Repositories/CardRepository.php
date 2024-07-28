<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\Part;

class CardRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Card::class;
    }

    public function getListCardByPartID(array $select, mixed $partId)
    {
        return $this->_model->select($select)
            ->whereHas("parts", function ($query) use ($partId) {
                $query->where(Part::_ID, $partId);
            })
            ->get();
    }

    public function findByID(mixed $cardId,$select = ["*"])
    {
        return $this->_model->select($select)->where(Card::_ID,$cardId)->whereNull(Card::_DELETED_AT)->first();
    }
}
