<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id'); // Primary key
            $table->unsignedBigInteger('booking_id'); // FK to bookings
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50);
            $table->enum('payment_status', ['pending', 'completed', 'failed']);
            $table->string('transaction_id', 255)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
