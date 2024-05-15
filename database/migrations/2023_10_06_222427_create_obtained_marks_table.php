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
        Schema::create('obtained_marks', function (Blueprint $table) {
            $table->id();
            $table->integer("create_paper_id");
            $table->bigInteger("student_id");
            $table->string("marks");
            $table->integer("session_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obtained_marks');
    }
};
