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
        Schema::create('supplier_payment_setoff_data', function (Blueprint $table) {
            $table->id('supplier_payment_setoff_data_id');
            $table->integer('supplier_payments_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number', 200)->index();
            $table->integer('reference_internal_number');
            $table->string('reference_external_number', 200);
            $table->integer('reference_document_number');
            $table->decimal('amount', 10, 2);
            $table->decimal('paid_amount', 10, 2);
            $table->decimal('return_amount', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->decimal('set_off_amount', 10, 2);
            $table->date('date');
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
        Schema::dropIfExists('supplier_payment_setoff_data');
    }
};
