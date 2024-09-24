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
        Schema::create('patment_voucher_items', function (Blueprint $table) {
            $table->id('payment_voucher_item_id')->index();
            $table->integer('payment_voucher_id')->index();
            $table->integer('internal_number');
            $table->string('external_number',255);
            $table->integer('gl_account_id')->index();
            $table->integer('gl_account_analysis_id')->index();
            $table->string('description',255);
            $table->decimal('amount',15,2);
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
        Schema::dropIfExists('patment_voucher_items');
    }
};
