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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id')->index();
            $table->string('supplier_code',50)->unique()->nullable();
            $table->string('supplier_name',200)->nullable();
            $table->tinyText('primary_address')->nullable();
            $table->string('primary_mobile_number',15)->nullable();
            $table->string('primary_fixed_number',15)->nullable();
            $table->string('primary_email',250)->nullable();
            $table->string('license_no',50)->nullable();
            $table->tinyText('google_map_link')->nullable();
            $table->integer('supplier_group_id')->nullable();
            $table->integer('supply_group_id')->nullable();
            $table->integer('supplier_status')->nullable();
            $table->boolean('supplier_product_code')->nullable();
            $table->boolean('credit_allowed')->nullable();
            $table->decimal('credit_amount_alert_limit',10,2)->nullable();
            $table->decimal('credit_amount_hold_limit',10,2)->nullable();
            $table->decimal('credit_period_alert_limit',10,2)->nullable();
            $table->decimal('credit_period_hold_limit',10,2)->nullable();
            $table->boolean('pd_cheque_allowed')->nullable();
            $table->decimal('pd_cheque_limit',10,2)->nullable();
            $table->decimal('pd_cheque_max_period',10,2)->nullable();
            $table->boolean('sms_notification')->nullable();
            $table->boolean('whatapp_notification')->nullable();
            $table->boolean('email_notification')->nullable();
            $table->tinyText('note')->nullable();

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
        Schema::dropIfExists('suppliers');
    }
};
