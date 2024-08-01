<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardInviteUser extends Model
{
    use HasFactory;
    CONST TABLE = 'board_invite_users';
    CONST _ID = 'id';
    CONST _BOARD_ID = 'board_id';
    CONST _EMAIL_INVITED = 'email_invited';
    CONST _ROLE_ID = 'role_id';

    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_BOARD_ID,
        self::_EMAIL_INVITED,
        self::_ROLE_ID,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];
}
