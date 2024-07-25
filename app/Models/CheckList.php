<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckList extends Model
{
    use HasFactory;
    const TABLE = 'check_lists';
    const _ID = 'id';
    const _NAME = 'name';
    const _CARD_ID = 'card_id';
    const _DELETED_AT = 'deleted_at';
    const _CREATED_AT = 'created_at';
    const _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_CARD_ID,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];

    public function checkListItems () {
        return $this->hasMany(CheckListItem::class, CheckListItem::_CHECK_LIST_ID,self::_ID)->whereNull(CheckListItem::_DELETED_AT);
    }
}
