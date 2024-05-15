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
        Schema::create('connect_teacher_student_books', function (Blueprint $table) {
            $table->id();
            $table->integer("teacher_id");
            $table->integer("class_id");
            $table->string("section");
            $table->integer("book_id");
            $table->integer("session_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connect_teacher_student_books');
    }
};
