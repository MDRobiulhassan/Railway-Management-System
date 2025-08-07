<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoodOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('food_orders', function (Blueprint $table) {
            $table->id('order_id'); // PK auto-increment
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('food_id');
            $table->integer('quantity');
            $table->timestamps();

            // Foreign keys
            $table->foreign('booking_id')->references('booking_id')->on('bookings')->onDelete('cascade');
            $table->foreign('food_id')->references('food_id')->on('food_items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('food_orders');
    }
}
