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
        return $this->_model
            ->select(
                Activity::TABLE . '.' . Activity::_ID,
                Activity::TABLE . '.' . Activity::_USER_ID,
                Activity::TABLE . '.' . Activity::_CONTENT,
                Activity::TABLE . '.' . Activity::_TIME,
                Activity::TABLE . '.' . Activity::_UPDATED_AT,
                Activity::TABLE . '.' . Activity::_CARD_ID,
                User::TABLE . '.' . User::_ID,
                User::TABLE . '.' . User::_NAME,
                User::TABLE . '.' . User::_AVATAR,
            )
            ->where(
                Activity::TABLE . '.' . Activity::_CARD_ID, $cardID,
            )
            ->whereNull(Activity::TABLE . '.' . Activity::_DELETED_AT)
            ->leftJoin(
                User::TABLE,
                User::TABLE . '.' . User::_ID,
                Activity::TABLE . '.' . Activity::_USER_ID
            )
            ->whereNull(User::TABLE . '.' . User::_DELETED_AT)
            ->orderBy(Activity::TABLE . '.' . Activity::_CREATED_AT, 'desc')
            ->get();
    }
}
