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
        Schema::create('supplier_payment_bank_slips', function (Blueprint $table) {
            $table->id('supplier_payment_bank_slip_id')->index();
            $table->integer('supplier_payment_id')->index();
            $table->integer('internal_number');
            $table->string('external_number');
            $table->string('reference');
            $table->time('slip_time');
            $table->date('slip_date');



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
        Schema::dropIfExists('supplier_receipt_bank_slips');
    }
};
