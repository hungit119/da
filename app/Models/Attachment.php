<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    CONST TABLE = 'attachments';

    CONST _ID = 'id';

    CONST _URL = 'url';
    CONST _CONTENT = 'content';
    CONST _TYPE = 'type';
    CONST _CARD_ID = 'card_id';
    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    CONST TYPE_IMAGE = 1;

    protected $fillable = [
        self::_ID,
        self::_URL,
        self::_CONTENT,
        self::_TYPE,
        self::_CARD_ID,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];
}
