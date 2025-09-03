<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('nid_number', 50)->unique();
            $table->string('photo')->nullable();
            $table->date('dob')->nullable();
            $table->boolean('nid_verified')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

