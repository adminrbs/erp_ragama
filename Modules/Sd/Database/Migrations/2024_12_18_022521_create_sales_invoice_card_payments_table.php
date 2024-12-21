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
        Schema::create('sales_invoice_card_payments', function (Blueprint $table) {
            $table->id('sales_invoice_card_payment_id');
            $table->integer('sales_invoice_id');
            $table->integer('internal_number');
            $table->string('external_number',200);
            $table->decimal('amount',12,2);
            $table->integer('card_no');
            $table->integer('card_bank_id');
            $table->string('cardType',50);
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
        Schema::dropIfExists('sales_invoice_card_payments');
    }
};
