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
        Schema::create('direct_card_bundle_data', function (Blueprint $table) {
            $table->id('direct_card_bundle_data_id');
            $table->integer('direct_card_bundles_id')->index();
            $table->string('internal_number',200)->nullable()->index();
            $table->string('external_number',200)->nullable()->index();
            $table->integer('branch_id')->nullable()->index();
            $table->integer('customer_receipt_id')->nullable()->index();
            $table->integer('customer_receipt_setoff_data_id')->nullable()->index();
            $table->decimal('amount',10,2)->nullable();
            $table->integer('cashier_id')->nullable()->index();
            $table->integer('collector_id')->nullable()->index();
            $table->date('card_bundle_date')->nullable();
            $table->integer('sales_invoice_Id')->nullable()->index();
            $table->string('remarks',200)->nullable();
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
        Schema::dropIfExists('direct_card_bundle_data');
    }
};
