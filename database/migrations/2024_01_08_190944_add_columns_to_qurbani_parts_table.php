<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('qurbani_parts', function (Blueprint $table) {
            $table->bigInteger("serial_no");
            $table->string("full_name");
            $table->string("address");
            $table->string("phone_no");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qurbani_parts', function (Blueprint $table) {
            $table->bigInteger("serial_no");
            $table->string("full_name");
            $table->string("address");
            $table->string("phone_no");
        });
    }
};
