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

    public function getByPartIDAndCardID($partID, $cardID)
    {
        return $this->_model
            ->where(PartHasCard::_PART_ID, $partID)
            ->where(PartHasCard::_CARD_ID, $cardID)
            ->whereNull(PartHasCard::_DELETED_AT)
            ->first();
    }

    public function updateCardToPart(mixed $cardID, mixed $sourcePartID, mixed $destinationPartID)
    {
        return $this->_model
            ->where(PartHasCard::_CARD_ID, $cardID)
            ->where(PartHasCard::_PART_ID,$sourcePartID)
            ->whereNull(PartHasCard::_DELETED_AT)
            ->update([PartHasCard::_PART_ID => $destinationPartID]);
    }
}
