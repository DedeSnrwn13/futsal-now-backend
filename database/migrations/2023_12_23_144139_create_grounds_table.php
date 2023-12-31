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
        Schema::create('grounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_arena_id');
            $table->string('name');
            $table->text('description');
            $table->double('rental_price')->comment('per hour');
            $table->integer('capacity');
            $table->boolean('is_available')->default(true);
            $table->json('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grounds');
    }
};
