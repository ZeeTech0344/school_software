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
        Schema::create('create_papers', function (Blueprint $table) {
            $table->id();
            $table->integer("connect_teacher_id");
            $table->integer("marks");
            $table->integer("create_exam_id");
            $table->integer("session_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('create_papers');
    }
};
