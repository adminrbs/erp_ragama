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
        Schema::create('sales_invoice_tracking_inqueries', function (Blueprint $table) {
            $table->id('sales_invoice_tracking_inquery_id')->index();
            $table->integer('sales_invoice_Id')->index();
            $table->integer('created_by')->index();
            $table->date('inqeury_start_date');
            $table->date('inqeury_end_date')->nullable();
            $table->int('status')->default(0); // 0 = pending , 1 = cancelled, 2 = completed
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
        Schema::dropIfExists('sales_invoice_tracking_inqueries');
    }
};
