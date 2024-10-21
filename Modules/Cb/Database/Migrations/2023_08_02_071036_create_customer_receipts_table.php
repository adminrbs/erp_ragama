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
        Schema::create('customer_receipts', function (Blueprint $table) {
            $table->id('customer_receipt_id')->index();
            $table->integer('internal_number');
            $table->string('external_number',50);
            $table->integer('branch_id');
            $table->integer('customer_id');
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
            $table->string('your_reference',200)->nullable();
            $table->timestamps();

            $table->index('customer_receipt_id','cus_receiptid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_receipts');
    }
};
