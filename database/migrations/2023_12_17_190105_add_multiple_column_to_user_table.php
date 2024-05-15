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
        Schema::table('admissions', function (Blueprint $table) {
            $table->string("guardian")->nullable();
            $table->string("guardian_relation")->nullable();
            $table->string("father_cnic")->nullable();
            $table->string("father_occupation")->nullable();
            $table->string("phone_no")->nullable();
            $table->string("previous_madrissa")->nullable();
            $table->string("previous_madrissa_education")->nullable();
            $table->string("previous_school")->nullable();
            $table->string("previous_school_education")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            //
        });
    }
};
