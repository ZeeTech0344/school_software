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
        Schema::create('bank_outsource_amounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("invoice_no");
            $table->string("given_by");
            $table->string("address");
            $table->string("phone_no");
            $table->string("bank_name");
            $table->string("fund_type");
            $table->string("payment_type");
            $table->string("amount");
            $table->string("remarks")->nullable();
            $table->bigInteger("recieved_by");
            $table->string("amount_status")->default("In");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_outsource_amounts');
    }
};
