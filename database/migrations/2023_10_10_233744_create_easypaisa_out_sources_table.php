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
        Schema::create('easypaisa_out_sources', function (Blueprint $table) {
            $table->id();
            $table->string("amount");
            $table->string("remarks");
            $table->string("amount_status")->default("In");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('easypaisa_out_sources');
    }
};
