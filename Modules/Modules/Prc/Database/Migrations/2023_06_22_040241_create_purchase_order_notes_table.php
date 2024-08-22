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
        Schema::create('purchase_order_notes', function (Blueprint $table) {
            $table->id('purchase_order_Id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number')->default(0);
            $table->date('purchase_order_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_name',200)->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->integer('deliver_type_id')->nullable();
            $table->date('deliver_date_time')->nullable();
            $table->tinyText('delivery_instruction')->nullable();
            $table->integer('status')->default(0);
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('document_number')->nullable();
            $table->string('your_reference_number',50)->nullable();
            $table->timestamps();

            $table->index('purchase_order_Id','purc_orderId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_notes');
    }
};
