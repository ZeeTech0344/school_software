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
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->integer("admission_year");
            $table->string("register_no");
            $table->integer("roll_no");
            $table->string("admission_date");
            $table->integer("class_id");
            $table->string("section")->nullable();
            $table->string("shift");
            $table->string("category");
            $table->string("name");
            $table->string("father_name");
            $table->string("dob");
            $table->string("mobile_no");
            $table->string("address");
            $table->string("image")->nullable();
            $table->string("status");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
