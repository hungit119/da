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
        Schema::create('template_has_boards', function (Blueprint $table) {
            $table->integer('template_id');
            $table->integer('board_id');
            $table->integer('deleted_at')->nullable();
            $table->timestamps();

            $table->primary(['template_id', 'board_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_has_boards');
    }
};
