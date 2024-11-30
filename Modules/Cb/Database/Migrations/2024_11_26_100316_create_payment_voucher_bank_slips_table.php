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
        Schema::create('payment_voucher_bank_slips', function (Blueprint $table) {
            $table->id('payment_voucher_bank_slip_id');
            $table->integer('payment_voucher_id');
            $table->integer('internal_number');
            $table->string('external_number',200);
            $table->string('reference',255);
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
        Schema::dropIfExists('payment_voucher_bank_slips');
    }
};
