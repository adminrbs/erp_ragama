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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id('sales_order_Id')->index();
            $table->integer('internal_number')->nullable();
            $table->string('external_number',50)->nullable();
            $table->date('order_date_time')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('order_status_id')->default(0);
            $table->integer('employee_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->integer('order_type')->default(0); 
            $table->integer('payment_term_id')->nullable();
            $table->integer('deliver_type_id')->nullable();
            $table->date('expected_date_time')->nullable();
            $table->tinyText('delivery_instruction')->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default("Pending");
            $table->integer('prepaired_by');
            $table->integer('approved_by')->nullable();
            $table->string('your_reference_number',50);
            $table->integer('document_number')->nullable();
            $table->string('gps_latitude',45)->nullable();
            $table->string('gps_longitude',45)->nullable();
            $table->integer('merged_order_id')->nullable();
            $table->integer('block_request_sent')->default(0);
            $table->integer('customer_block_id')->nullable();
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
        Schema::dropIfExists('sales_oders');
    }
};
