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
        Schema::create('deliveryconfirmations', function (Blueprint $table) {
            $table->id('deliveryconfirmations_id');
            $table->integer('sales_invoice_Id')->index();
            $table->boolean('delivered');
            $table->boolean('Seal');
            $table->boolean('Signature');
            $table->boolean('Cash');
            $table->boolean('Cheque');
            $table->boolean('cancel');
            $table->boolean('status')->default(0);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('deliveryconfirmations');
    }
};
