<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    CONST TABLE = 'boards';
    CONST _ID = 'id';
    CONST _NAME = 'name';
    CONST _AVATAR = 'avatar';
    CONST _TYPE = 'type';
    CONST _DELETED_AT = 'deleted_at';
    CONST _CREATED_AT = 'created_at';
    CONST _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_AVATAR,
        self::_TYPE,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];

    public function parts (){
        return $this->hasMany(Part::class,Part::_BOARD_ID,self::_ID)->whereNull(Part::_DELETED_AT);
    }
}
