<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartHasCard extends Model
{
    use HasFactory;

    CONST TABLE = 'part_has_cards';
    CONST _PART_ID = 'part_id';
    CONST _CARD_ID = 'card_id';

    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_PART_ID,
        self::_CARD_ID,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];
}
