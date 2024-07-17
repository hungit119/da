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
        Schema::create('part_has_cards', function (Blueprint $table) {
            $table->integer("part_id");
            $table->integer("card_id");
            $table->integer("deleted_at")->nullable();
            $table->timestamps();

            $table->primary(["part_id","card_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_has_cards');
    }
};
