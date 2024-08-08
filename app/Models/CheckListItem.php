<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckListItem extends Model
{
    use HasFactory;

    const TABLE          = 'check_list_items';
    const _ID            = 'id';
    const _NAME          = 'name';
    const _CHECK_LIST_ID = 'check_list_id';
    const _IS_CHECKED    = 'is_checked';
    const _TIME_START    = 'time_start';
    const _JOB_SCORE      = 'job_score';
    const _JOB_DONE_ON_TIME = 'job_done_on_time';
    const _ESTIMATED_TIME_END = 'estimate_time_end';
    const _TIME_END      = 'time_end';
    const _DELETED_AT    = 'deleted_at';
    const _CREATED_AT    = 'created_at';
    const _UPDATED_AT    = 'updated_at';
    protected $fillable   = [
        self::_ID,
        self::_NAME,
        self::_CHECK_LIST_ID,
        self::_IS_CHECKED,
        self::_TIME_START,
        self::_TIME_END,
        self::_JOB_SCORE,
        self::_JOB_DONE_ON_TIME,
        self::_ESTIMATED_TIME_END,
        self::_DELETED_AT,
        self::_CREATED_AT,
        self::_UPDATED_AT
    ];
    protected $attributes = [
        self::_IS_CHECKED => 0,
    ];

    const JOB_DONE_ON_TIME = 1;
}
