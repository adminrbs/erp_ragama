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
        Schema::create('sales_invoice_bank_transfers', function (Blueprint $table) {
            $table->id('sales_invoice_bank_transfer_id');
            $table->integer('sales_invoice_id');
            $table->integer('internal_number');
            $table->integer('external_number');
            $table->decimal('amount',12,2);
            $table->date('bank_transfer_date');
            $table->string('bank_transfer_reference');

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
        Schema::dropIfExists('sales_invoice_bank_transfers');
    }
};
