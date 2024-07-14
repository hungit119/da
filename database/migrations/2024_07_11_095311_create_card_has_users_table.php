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
        Schema::create('card_has_users', function (Blueprint $table) {
            $table->integer('card_id');
            $table->integer('user_id');
            $table->integer('deleted_at')->nullable();
            $table->timestamps();

            $table->primary(['card_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_has_users');
    }
};
