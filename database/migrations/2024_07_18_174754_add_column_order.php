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
        Schema::table('parts', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn('position');
        });
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
};
