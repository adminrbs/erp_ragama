<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id('payment_voucher_id');
            $table->integer('internal_number');
            $table->string('external_number',255);
            $table->date('transaction_date');
            $table->integer('supplier_id');
            $table->integer('payee_id');
            $table->string('payee_name',255);
            $table->integer('payment_method_id');
            $table->integer('branch_id');
            $table->decimal('total_amount');
            $table->integer('gl_account_id');
            $table->integer('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
