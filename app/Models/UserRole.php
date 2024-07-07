<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    const TABLE = 'user_roles';
    const _USER_ID    = 'user_id';
    const _ROLE_ID    = 'role_id';
    const _DELETED_AT = 'deleted_at';
    protected $fillable = [
        self::_USER_ID,
        self::_ROLE_ID,
        self::_DELETED_AT
    ];
}
