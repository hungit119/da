<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    const TABLE       = 'boards';
    const _ID         = 'id';
    const _NAME       = 'name';
    const _AVATAR     = 'avatar';
    const _TYPE       = 'type';
    const _DELETED_AT = 'deleted_at';
    const _CREATED_AT = 'created_at';
    const _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_AVATAR,
        self::_TYPE,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];

    const ROLE_MANAGER = "Manager";
    const ROLE_GUEST = "Guest";

    public function parts()
    {
        return $this->hasMany(Part::class, Part::_BOARD_ID, self::_ID)->whereNull(Part::_DELETED_AT);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, BoardHasUser::TABLE, BoardHasUser::_BOARD_ID,
            BoardHasUser::_USER_ID)->withPivot([
                BoardHasUser::_USER_ID,
                BoardHasUser::_ROLE_ID,
                BoardHasUser::_STATUS_ACCEPT,
                BoardHasUser::_DELETED_AT
        ]);
    }
}
