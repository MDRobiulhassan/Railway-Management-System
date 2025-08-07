<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNidDbTable extends Migration
{
    public function up()
    {
        Schema::create('nid_db', function (Blueprint $table) {
            $table->id('user_id'); 
            $table->string('nid_number', 50)->unique();
            $table->string('name', 255);
            $table->date('dob');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nid_db');
    }
}
