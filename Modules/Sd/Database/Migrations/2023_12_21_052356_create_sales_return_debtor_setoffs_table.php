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
        Schema::create('sales_return_debtor_setoffs', function (Blueprint $table) {
            $table->id('sales_return_debtor_setoff_id');
            $table->integer('debtors_ledger_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('sales_return_Id')->index();
            $table->decimal('setoff_amount',15,2);

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
        Schema::dropIfExists('sales_return_debtor_setoffs');
    }
};
