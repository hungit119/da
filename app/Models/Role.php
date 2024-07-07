<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const TABLE = 'roles';
    const _ID = 'id';
    const _NAME = 'name';
    const _DESCRIPTION = 'description';
    const _DELETED_AT = 'deleted_at';
    protected $fillable = [
        self::_ID,
        self::_NAME,
        self::_DESCRIPTION,
        self::_DELETED_AT
    ];

    const ROLE_LEADER = 'Leader';
}
