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
        Schema::create('holiday_diaries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("teacher_id");
            $table->bigInteger("student_id");
            $table->bigInteger("attach_book_id");
            $table->string("ghar_kay_mamoolat");
            $table->string("questions");
            $table->string("sonay_ka_waqt");
            $table->string("hazrinmaz");
            $table->string("hidayat_mualam");
            $table->string("hidayat_sarparast");
            $table->integer("session_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday_diaries');
    }
};
