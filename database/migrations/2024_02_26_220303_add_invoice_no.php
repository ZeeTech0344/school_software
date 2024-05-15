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
        Schema::table('bank_outsource_amounts', function (Blueprint $table) {
            // $table->bigInteger("invoice_no");
            // $table->string("given_by");
            // $table->string("phone_no");
            // $table->string("address");
            // $table->string("bank_type");
            // $table->string("payment_type");
            // $table->bigInteger("recieved_by");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_outsource_amounts', function (Blueprint $table) {
            // $table->bigInteger("invoice_no")->after("id");
            // $table->string("given_by")->after("invoice_no");
            // $table->string("phone_no")->after("given_by");
            // $table->string("address")->after("phone_no");
            // $table->string("bank_type");
            // $table->string("payment_type");
            // $table->bigInteger("recieved_by");
        });
    }
};
