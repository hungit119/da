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
        Schema::table('board_has_users', function (Blueprint $table) {
            $table->string('role_id')->nullable()->default("Leader");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('board_has_users', function (Blueprint $table) {
            $table->dropColumn('role_id');
        });
    }
};
