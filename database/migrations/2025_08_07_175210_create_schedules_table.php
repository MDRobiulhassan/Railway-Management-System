<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id'); // Primary key
            $table->unsignedBigInteger('train_id'); // FK to trains
            $table->unsignedBigInteger('source_id'); // FK to source station
            $table->unsignedBigInteger('destination_id'); // FK to destination station
            $table->dateTime('arrival_time');
            $table->dateTime('departure_time');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
            $table->foreign('source_id')->references('station_id')->on('stations')->onDelete('cascade');
            $table->foreign('destination_id')->references('station_id')->on('stations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
