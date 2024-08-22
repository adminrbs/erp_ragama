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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id('supplier_payment_id')->index();
            $table->integer('internal_number');
            $table->string('external_number',50);
            $table->integer('branch_id');
            $table->integer('supplier_id');
            $table->integer('collector_id');
            $table->integer('cashier_id');
            $table->date('receipt_date');
            $table->integer('receipt_method_id');
            $table->integer('gl_account_id');
            $table->decimal('amount',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('round_up',10,2);
            $table->integer('advance');
            $table->integer('document_number');
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
        Schema::dropIfExists('supplier_receipts');
    }
};
