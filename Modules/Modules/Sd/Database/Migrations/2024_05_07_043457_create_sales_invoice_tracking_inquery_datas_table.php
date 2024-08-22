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
        Schema::create('sales_invoice_tracking_inquery_datas', function (Blueprint $table) {
            $table->id('sales_invoice_tracking_inquery_data_id')->index();
            $table->integer('sales_invoice_tracking_inquery_id')->index();
            $table->integer('inquery_person_id')->index();
            $table->string('inquery_person_statment',255)->nullable();

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
        Schema::dropIfExists('sales_invoice_tracking_inquery_datas');
    }
};
