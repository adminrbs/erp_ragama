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
        Schema::create('sales_invoice_payments', function (Blueprint $table) {
            $table->id('sales_invoice_payment_id');
            $table->integer('sales_invoice_id');
            $table->integer('internal_number');
            $table->string('external_number',200);
            $table->decimal('card_amount',12,2)->nullable();
            $table->integer('card_no')->nullable();
            $table->integer('card_bank_id')->nullable();
            $table->string('cardType',50)->nullable();
            $table->decimal('cheque_amount',15,2)->nullable();
            $table->integer('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->integer('Cheque_Bank_id')->nullable();
            $table->integer('cheque_bank_branch_id')->nullable();
            $table->decimal('bank_transfer_amount',12,2)->nullable();
            $table->date('bank_transfer_date')->nullable();
            $table->string('bank_transfer_reference')->nullable();
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
        Schema::dropIfExists('sales_invoice_payments');
    }
};
