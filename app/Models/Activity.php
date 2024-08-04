<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    const TABLE = 'activities';

    const _ID         = 'id';
    const _USER_ID    = 'user_id';
    const _CARD_ID    = 'card_id';
    const _CONTENT    = 'content';
    const _PARENT_ID  = 'parent_id';
    const _TIME       = 'time';
    const _DELETED_AT = 'deleted_at';
    const _CREATED_AT = 'created_at';
    const _UPDATED_AT = 'updated_at';

    protected $fillable = [
        self::_ID,
        self::_USER_ID,
        self::_CARD_ID,
        self::_CONTENT,
        self::_TIME,
        self::_PARENT_ID,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, self::_PARENT_ID)->whereNull(Activity::_DELETED_AT);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, self::_PARENT_ID)->whereNull(Activity::_DELETED_AT)->with("activities.user");
    }

    public function user()
    {
        return $this->hasOne(User::class, User::_ID, self::_USER_ID)->whereNull(User::_DELETED_AT);
    }
}
