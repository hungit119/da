<?php

namespace App\Repositories;

use App\Models\BoardInviteUser;

class BoardInviteUserRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return BoardInviteUser::class;
    }

    public function getByBoardID(mixed $boardID)
    {
        return $this->_model->where(BoardInviteUser::_BOARD_ID,
            $boardID)->whereNull(BoardInviteUser::_DELETED_AT)->get();
    }

    public function findByEmailAndBoardID(mixed $userInviteEmail,$boardID)
    {
        return $this->_model
            ->where(BoardInviteUser::_EMAIL_INVITED,$userInviteEmail)
            ->where(BoardInviteUser::_BOARD_ID,$boardID)
            ->whereNull(BoardInviteUser::_DELETED_AT)
            ->first();
    }

    public function updateByEmailBoardID(mixed $email, mixed $boardID, array $array)
    {
        return $this->_model
            ->where(BoardInviteUser::_EMAIL_INVITED,$email)
            ->where(BoardInviteUser::_BOARD_ID,$boardID)
            ->update($array);

    }
}
