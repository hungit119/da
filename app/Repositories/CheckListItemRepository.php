<?php

namespace App\Repositories;

use App\Models\CheckListItem;
use Illuminate\Support\Facades\DB;

class CheckListItemRepository extends BaseRepository
{

    public function getModel()
    {
        // TODO: Implement getModel() method.
        return CheckListItem::class;
    }

    public function getFeature(mixed $userID)
    {
        return DB::table('users as u')
            ->join('check_lists as cl', 'u.id', '=', 'cl.user_id')
            ->join('check_list_items as cli', 'cl.id', '=', 'cli.check_list_id')
            ->select(
                'u.year_experience',
                DB::raw('COUNT(CASE WHEN cli.is_checked = 1 THEN cli.id END) as number_of_job_done'),
                DB::raw('IFNULL(AVG(CASE WHEN cli.is_checked = 1 THEN (cli.time_end - cli.time_start) / 3600000 END), 0) as time_done_average'),
                DB::raw('COUNT(CASE WHEN cli.job_done_on_time = 1 THEN cli.id END) as total_of_job_done_on_time'),
                DB::raw('COUNT(cli.id) as total_of_job'),
                DB::raw('IFNULL(SUM((cli.time_end - cli.time_start) / 3600000), 0) as completed_time_job'),
                DB::raw('IFNULL(AVG(cli.job_score), 0) AS average_job_score'),
            )
            ->whereNull('cli.deleted_at')
            ->whereNull('cl.deleted_at')
            ->whereNull('u.deleted_at')
            ->where('u.id', '=', $userID)
            ->groupBy('u.id', 'u.year_experience')
            ->first();
    }
}
