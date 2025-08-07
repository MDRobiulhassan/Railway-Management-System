<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('booking_id'); // Primary key
            $table->unsignedBigInteger('user_id'); // FK to users
            $table->unsignedBigInteger('train_id'); // FK to trains
            $table->timestamp('booking_date')->useCurrent(); // Default CURRENT_TIMESTAMP
            $table->enum('status', ['pending', 'confirmed', 'cancelled']);
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
