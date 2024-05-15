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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("employee_no")->nullable();
            $table->string('employee_name')->nullable();
            //this is user name
            $table->string('name')->nullable();
            $table->string("employee_post")->nullable();
            $table->string('email')->nullable();
            $table->string('user_type')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string("cnic")->nullable();
            $table->string("phone_no")->nullable();
            $table->string("father_cnic")->nullable();
            $table->string("father_phone_no")->nullable();
            $table->string("basic_sallary")->nullable();
            $table->string("image")->nullable();
            // $table->integer("employee_branch")->nullable();
            $table->string("employee_status")->nullable();
            $table->string("account_for")->nullable();
            $table->string("joining")->nullable();
            $table->string("leaving")->nullable();
            $table->string("operator")->nullable();
            $table->timestamps();
            $table->rememberToken();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
