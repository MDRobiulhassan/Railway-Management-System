<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id'); // Primary key
            $table->unsignedBigInteger('booking_id'); // FK to bookings
            $table->unsignedBigInteger('train_id');   // FK to trains
            $table->unsignedBigInteger('seat_id');    // FK to seats
            $table->date('travel_date');
            $table->unsignedBigInteger('compartment_id'); // FK to compartments
            $table->enum('ticket_status', ['active', 'cancelled', 'used']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
            $table->foreign('seat_id')->references('seat_id')->on('seats')->onDelete('cascade');
            $table->foreign('compartment_id')->references('compartment_id')->on('compartments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
