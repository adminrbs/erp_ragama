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
        Schema::create('sales_invoice_cheque_payments', function (Blueprint $table) {
            $table->id('sales_invoice_cheque_payment_id');
            $table->integer('sales_invoice_id');
            $table->integer('internal_number');
            $table->string('external_number');
            $table->decimal('amount',15,2);
            $table->integer('cheque_no');
            $table->date('chqDate');
            $table->integer('cmbBank');
            $table->integer('cmbBankBranch');
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
        Schema::dropIfExists('sales_invoice_cheque_payments');
    }
};
