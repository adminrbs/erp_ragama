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
        Schema::create('customers', function (Blueprint $table) {
            
            $table->id('customer_id')->index();
            $table->string('customer_code',50)->unique()->nullable();
            $table->string('customer_name', 200);
            $table->integer('town')->nullable();
            $table->integer('route_id')->nullable()->index();
            $table->tinyText('primary_address')->nullable();
            $table->string('primary_mobile_number',15)->nullable();
            $table->string('primary_fixed_number',15)->nullable();
            $table->string('primary_email',250)->nullable();
            $table->integer('disctrict_id')->nullable()->index();
            $table->integer('town_id')->nullable()->index();
            $table->string('license_no',50)->nullable();
            $table->tinyText('google_map_link')->nullable()->nullable();
            $table->string('gps_latitude',45)->nullable()->nullable();
            $table->string('gps_longitude',45)->nullable()->nullable();
            $table->integer('customer_group_id')->nullable();
            $table->integer('customer_grade_id')->nullable();
            $table->boolean('deliver_primary_address')->nullable();
            $table->integer('customer_status')->nullable();
            $table->boolean('credit_allowed')->nullable();
            $table->decimal('credit_amount_alert_limit',10,2)->nullable();
            $table->decimal('credit_amount_hold_limit',10,2)->nullable();
            $table->decimal('credit_period_alert_limit',10,2)->nullable();
            $table->decimal('credit_period_hold_limit',10,2)->nullable();
            $table->boolean('pd_cheque_allowed')->nullable();
            $table->decimal('pd_cheque_limit',10,2)->nullable();
            $table->decimal('pd_cheque_max_period',10,2)->nullable();
            $table->integer('credit_control_type')->nullable();
            $table->integer('free_offer_allowed')->nullable();
            $table->boolean('promotion_allowed')->nullable();
            $table->boolean('sms_notification')->nullable();
            $table->boolean('whatapp_notification')->nullable();
            $table->boolean('email_notification')->nullable();
            $table->tinyText('note')->nullable();
            $table->integer('payment_term_id')->nullable()->index();
            $table->integer('marketing_route_id')->nullable()->index();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
