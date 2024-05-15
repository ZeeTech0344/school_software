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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->integer('voucher_no');
            $table->integer('class_id');
            $table->integer('student_id');
            $table->string('voucher_heads');
            $table->string('for_the_month');
            $table->string('last_date');
            $table->integer('fine')->default(0);
            $table->integer('arrears')->default(0);
            $table->string('before_due_date');
            $table->string('after_due_date');
            $table->string('recieved_amount')->default(0);
            $table->integer('session_id');
            $table->integer('arrears_cleared_voucher_id')->nullable();
            $table->string('voucher_type');
            $table->string('status');
            $table->string('amount_status')->default("In");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
