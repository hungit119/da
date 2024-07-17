<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    CONST TABLE = 'parts';

    CONST _ID = 'id';
    CONST _NAME = 'name';
    CONST _BOARD_ID = 'board_id';
    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_BOARD_ID,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];

    public function cards()
    {
        return $this->belongsToMany(Card::class,PartHasCard::TABLE,PartHasCard::_PART_ID,PartHasCard::_CARD_ID)->whereNull(PartHasCard::TABLE. '.' . PartHasCard::_DELETED_AT);
    }
}
