<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compartments', function (Blueprint $table) {
            $table->id('compartment_id');  // Primary key
            $table->unsignedBigInteger('train_id');  // Foreign key to trains
            $table->string('compartment_name', 10);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compartments');
    }
};
