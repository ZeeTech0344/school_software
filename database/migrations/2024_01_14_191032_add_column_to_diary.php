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
        Schema::table('diaries', function (Blueprint $table) {
            $table->string('sabaq_sunaya')->nullable();
            $table->string('samay_two')->nullable();
            $table->string('sunaya')->nullable();
            $table->string('manzil_para')->nullable();
            $table->string('samay_one')->nullable();
            $table->string('sabaq_sunai')->nullable();
            $table->string('samay_four')->nullable();
            $table->string('kacha_sabaq_sunaya')->nullable();
            $table->string('samay_three')->nullable();
            $table->string('dia')->nullable();
            $table->string('para_ya_teen_sabaq')->nullable();
            $table->string('bad_zuhr_hazir_hua')->nullable();
            $table->string('bad_maghrib_hazir_hua')->nullable();
            $table->string('subah_hazir_hua')->nullable();
            $table->string('tak_satrain')->nullable();
            $table->string('taa')->nullable();
            $table->string('ayat_no')->nullable();
            $table->string('para_no')->nullable();
            $table->string('surah')->nullable();
            $table->string('hazrinmaz')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diaries', function (Blueprint $table) {
            //
        });
    }
};
