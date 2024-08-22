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
        Schema::create('customer_receipt_cheques', function (Blueprint $table) {
            $table->id('customer_receipt_cheque_id');
            $table->integer('customer_receipt_id');
            $table->integer('internal_number');
            $table->string('external_number',200);
            $table->string('cheque_referenceNo',200);
            $table->string('cheque_number',200);
            $table->string('bank_code',50);
            $table->date('banking_date');
            $table->decimal('amount',10,2);
            $table->integer('bank_id');
            $table->integer('bank_branch_id');
            $table->integer('cheque_status');
            $table->date('cheque_deposit_date')->nullable();
            $table->date('cheque_dishonoured_date')->nullable();
            $table->timestamps();

            $table->index('customer_receipt_cheque_id','customerCheque_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_receipt_cheques');
    }
};
