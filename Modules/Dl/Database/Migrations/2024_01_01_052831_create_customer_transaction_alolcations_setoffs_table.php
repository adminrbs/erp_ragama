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
        Schema::create('customer_transaction_alocations_setoffs', function (Blueprint $table) {
            $table->id('customer_transaction_alocations_setoff_id');
            $table->integer('customer_transaction_alocation_id')->index();
            $table->string('internal_number',200)->index();
            $table->string('external_number',200)->index();
            $table->integer('debtor_ledger_id')->index();
            $table->string('reference_internal_number',200);
            $table->string('reference_external_number',200);
            $table->integer('reference_document_number');
            $table->integer('reference_debtor_ledger_id')->index();
            $table->decimal('set_off_amount',15,2)->index();
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
        Schema::dropIfExists('customer_transaction_alolcations_setoffs');
    }
};
