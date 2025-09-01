<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('compartments', function (Blueprint $table) {
            $table->string('class_name', 50)->after('compartment_name'); // add after compartment_name
        });
    }

    public function down(): void
    {
        Schema::table('compartments', function (Blueprint $table) {
            $table->dropColumn('class_name');
        });
    }
};
