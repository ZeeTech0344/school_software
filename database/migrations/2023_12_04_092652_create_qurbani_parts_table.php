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
        Schema::create('qurbani_parts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("qurbani_id");
            $table->bigInteger("total_parts");
            $table->string("total_parts_amount");
            $table->string("remarks");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qurbani_parts');
    }
};
