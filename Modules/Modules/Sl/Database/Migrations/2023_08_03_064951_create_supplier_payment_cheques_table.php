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
        Schema::create('supplier_payment_cheques', function (Blueprint $table) {
            $table->id('supplier_payment_cheque_id')->index();
            $table->integer('supplier_payment_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
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
            $table->integer('gl_account_id')->nullable()->index();
            $table->integer('dishonoured_by')->nullable()->index();
            $table->integer('cheque_dishonur_reason_id')->nullable()->index();
            $table->decimal('bank_charges',15,2)->nullable();
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
        Schema::dropIfExists('supplier_payment_cheques');
    }
};