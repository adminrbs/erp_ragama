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
        Schema::create('customer_payment_modes', function (Blueprint $table) {
            $table->id('customer_payment_method_id')->index();
            $table->string('customer_payment_method',200);
            $table->boolean('is_active')->default(1);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('customer_payment_method_id','cus_pay_MethId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_payment_modes');
    }
};
