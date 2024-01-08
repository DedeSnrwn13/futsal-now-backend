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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_id');
            $table->foreignId('user_id');
            $table->dateTime('order_date');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->double('total_price');
            $table->string('order_number')->unique();
            $table->string('order_status');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('promo_id')->nullable();
            $table->string('ref_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
