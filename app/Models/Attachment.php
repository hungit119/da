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
    CONST _CARD_ID = 'card_id';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_URL,
        self::_CONTENT,
        self::_CARD_ID,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];
}
