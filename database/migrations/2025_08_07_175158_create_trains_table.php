<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id('train_id');
            $table->string('train_name');
            $table->string('train_type', 50);
            $table->integer('total_seats');
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('destination_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('source_id')->references('station_id')->on('stations')->onDelete('cascade');
            $table->foreign('destination_id')->references('station_id')->on('stations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
