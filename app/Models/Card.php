<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    CONST TABLE = 'cards';

    CONST _ID = 'id';
    CONST _NAME = 'name';
    CONST _DESCRIPTION = 'description';
    CONST _START_DATE = 'start_date';
    CONST _DUE_DATE = 'due_date';
    CONST _BACKGROUND = 'background';
    CONST _LABEL = "label";
    CONST _POSITION = "position";
    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_DESCRIPTION,
        self::_START_DATE,
        self::_DUE_DATE,
        self::_BACKGROUND,
        self::_LABEL,
        self::_POSITION,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT,
    ];

    public function parts()
    {
        return $this->belongsToMany(Part::class,PartHasCard::TABLE,PartHasCard::_CARD_ID,PartHasCard::_PART_ID)->whereNull(PartHasCard::TABLE. '.' . PartHasCard::_DELETED_AT);
    }
    public function attachments () {
        return $this->hasMany(Attachment::class, Attachment::_CARD_ID, self::_ID)->whereNull(Attachment::_DELETED_AT);
    }
    public function checklists () {
        return $this->hasMany(Checklist::class, Checklist::_CARD_ID, self::_ID);
    }
}
