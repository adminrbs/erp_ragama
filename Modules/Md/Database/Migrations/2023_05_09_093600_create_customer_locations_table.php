<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_locations', function (Blueprint $table) {
            $table->integer('customer_id')->index();
            $table->integer('location_id')->index();
            $table->boolean('credit_allowed')->nullable();
            $table->decimal('credit_amount_alert_limit',10,2)->nullable();
            $table->decimal('credit_amount_hold_limit',10,2)->nullable();
            $table->decimal('credit_period_alert_limit',10,2)->nullable();
            $table->decimal('credit_period_hold_limit',10,2)->nullable();
            $table->boolean('pd_cheque_allowed')->nullable();
            $table->decimal('pd_cheque_limit',10,2)->nullable();
            $table->decimal('pd_cheque_max_period',10,2)->nullable();
            $table->timestamps();

            $table->unique(['customer_id','location_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_locations');
    }
};
