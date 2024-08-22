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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id')->index();
            $table->string('employee_code',50)->nullable();
            $table->string('employee_name');
            $table->string('full_name');
            $table->string('nick_name');
            $table->string('nic_no');
            $table->string('emergency_contact_number');
            $table->string('date_of_birth');
            $table->string('certificate_file_no');
            $table->string('file_no');
            $table->string('from_town');
            $table->string('gps');
            $table->string('office_mobile',15)->nullable();
            $table->string('office_email',250)->nullable();
            $table->string('persional_mobile',15)->nullable();
            $table->string('persional_fixed',15)->nullable();
            $table->string('persional_email',250)->nullable();
            $table->tinyText('address')->nullable();
            $table->integer('desgination_id');
            $table->integer('report_to');
            $table->date('date_of_joined')->nullable();
            $table->date('date_of_resign')->nullable();
            $table->integer('status_id');
            $table->string('mobile_user_name')->nullable();
            $table->string('mobile_app_password')->nullable();
            $table->integer('credit_amount_alert_limit')->nullable();
            $table->integer('credit_amount_hold_limit')->nullable();
            $table->integer('credit_period_alert_limit')->nullable();
            $table->integer('credit_period_hold_limit')->nullable();
            $table->integer('pd_cheque_max_period')->nullable();
            $table->integer('pd_cheque_limit')->nullable();
            $table->tinyText('note')->nullable();
            $table->string('code',200)->nullable();
            $table->string('employee_attachments')->nullable();
            $table->decimal('target',15,2)->nullable();
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
        Schema::dropIfExists('employees');
    }
};
