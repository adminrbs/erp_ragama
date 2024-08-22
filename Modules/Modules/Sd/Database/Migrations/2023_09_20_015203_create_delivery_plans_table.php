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
        Schema::create('delivery_plans', function (Blueprint $table) {
            $table->id('delivery_plan_id');
            $table->string('delivery_ref_no',45);
            $table->integer('internal_number');
            $table->string('external_number',45);
            $table->integer('vehicle_id');
            $table->integer('sales_rep_id');
            $table->integer('driver_id');
            $table->integer('helper_id');
            $table->integer('route_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('status');
            $table->integer('document_number');
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
        Schema::dropIfExists('delivery_plans');
    }
};
