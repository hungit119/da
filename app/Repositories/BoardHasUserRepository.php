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

    public function findByUserIDAndBoardID($userID, mixed $boardId)
    {
        return $this->_model->where(BoardHasUser::_USER_ID, $userID)
            ->where(BoardHasUser::_BOARD_ID, $boardId)
            ->whereNull(BoardHasUser::_DELETED_AT)
            ->first();
    }
}
