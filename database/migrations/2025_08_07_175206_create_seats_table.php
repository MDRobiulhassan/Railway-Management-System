<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id('seat_id');  // Primary key
            $table->unsignedBigInteger('train_id');  // Foreign key to trains
            $table->unsignedBigInteger('compartment_id');  // Foreign key to compartments
            $table->string('seat_number', 10);
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
            $table->foreign('compartment_id')->references('compartment_id')->on('compartments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
