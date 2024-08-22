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
        Schema::create('employee_customers', function (Blueprint $table) {
            $table->integer('employee_id');
            $table->integer('customer_id');
            $table->boolean('credit_allowed');
            $table->decimal('credit_amount_alert_limit',10,2);
            $table->decimal('credit_amount_hold_limit',10,2);
            $table->decimal('credit_period_alert_limit',10,2);
            $table->decimal('credit_period_hold_limit',10,2);
            $table->boolean('pd_cheque_allowed');
            $table->decimal('pd_cheque_limit',10,2);
            $table->decimal('pd_cheque_max_period',10,2);
            $table->timestamps();

            $table->index('employee_id','employee_id');
            $table->index('customer_id','customer_id');

            $table->unique(['employee_id','customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_customers');
    }
};
