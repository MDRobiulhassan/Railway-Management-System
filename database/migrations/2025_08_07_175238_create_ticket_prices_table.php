<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketPricesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_prices', function (Blueprint $table) {
            $table->id('price_id'); // PK
            $table->unsignedBigInteger('train_id');
            $table->unsignedBigInteger('compartment_id');
            $table->decimal('base_price', 10, 2);
            $table->timestamps();

            // Foreign keys
            $table->foreign('train_id')->references('train_id')->on('trains')->onDelete('cascade');
            $table->foreign('compartment_id')->references('compartment_id')->on('compartments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_prices');
    }
}
