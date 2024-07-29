<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardHasUser extends Model
{
    use HasFactory;

    CONST TABLE = 'board_has_users';
    CONST _BOARD_ID = 'board_id';
    CONST _USER_ID = 'user_id';
    CONST _ROLE_ID = 'role_id';
    CONST _STATUS_ACCEPT = 'status_accept';

    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    CONST STATUS_ACCEPTED = 1;

    protected $fillable = [
        self::_BOARD_ID,
        self::_USER_ID,
        self::_ROLE_ID,
        self::_STATUS_ACCEPT,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];
}
