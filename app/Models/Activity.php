<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    CONST TABLE = 'activities';

    CONST _ID = 'id';
    CONST _USER_ID = 'user_id';
    CONST _CARD_ID = 'card_id';
    CONST _CONTENT = 'content';
    CONST _TIME = 'time';
    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_USER_ID,
        self::_CARD_ID,
        self::_CONTENT,
        self::_TIME,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];

}
