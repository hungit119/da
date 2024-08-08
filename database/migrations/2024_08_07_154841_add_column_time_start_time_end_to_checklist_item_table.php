<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('check_list_items', function (Blueprint $table) {
            $table->integer('time_start')->nullable();
            $table->integer('time_end')->nullable();
            $table->integer('job_score')->nullable();
            $table->integer('job_done_on_time')->nullable();
            $table->integer('estimate_time_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_list_items', function (Blueprint $table) {
            $table->dropColumn('time_start');
            $table->dropColumn('time_end');
            $table->dropColumn('job_score');
            $table->dropColumn('job_done_on_time');
            $table->dropColumn('estimate_time_end');
        });
    }
};
