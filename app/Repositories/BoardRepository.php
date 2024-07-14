<?php

namespace App\Repositories;

use App\Models\Board;
use App\Models\BoardHasUser;

class BoardRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return Board::class;
    }

    public function getListBoardByUserID(array $select, mixed $userId)
    {
        return $this->_model->select($select)
            ->leftJoin(BoardHasUser::TABLE, BoardHasUser::TABLE . '.' . BoardHasUser::_BOARD_ID,
                Board::TABLE . '.' . Board::_ID)
            ->whereNull(Board::TABLE . '.' . Board::_DELETED_AT)
            ->where(BoardHasUser::TABLE . '.' . BoardHasUser::_USER_ID, $userId)
            ->get();
    }
}
