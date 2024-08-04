<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\User;

class ActivitiesRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Activity::class;
    }

    public function findByCardID(mixed $cardID)
    {
        return $this->_model::with(["activities.user","user"])
            ->select(
                Activity::_ID,
                Activity::_USER_ID,
                Activity::_CONTENT,
                Activity::_TIME,
                Activity::_UPDATED_AT,
                Activity::_CARD_ID,
                Activity::_PARENT_ID,
            )
            ->where(
                Activity::_CARD_ID, $cardID,
            )
            ->whereNull(Activity::_PARENT_ID)
            ->whereNull(Activity::_DELETED_AT)
            ->orderBy(Activity::_CREATED_AT, 'desc')
            ->get();
    }
}
