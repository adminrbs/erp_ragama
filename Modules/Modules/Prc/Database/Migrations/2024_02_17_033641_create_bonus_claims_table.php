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
        Schema::create('bonus_claims', function (Blueprint $table) {
            $table->id('bonus_claim_Id');
            $table->string('internal_number',200)->nullable();
            $table->string('external_number',200)->nullable();
            $table->date('bonus_claim_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_name',200)->nullable();
            /* $table->string('supplier_address',200); */
            $table->integer('purchase_order_id')->default(0);//need to change
            $table->string('supppier_invoice_number',200)->default(0);//need to change
            $table->decimal('invoice_amount',10,2)->nullable();
            $table->date('payment_due_date')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->decimal('adjustment_amount',10,2)->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->integer('document_number')->default(1);
            $table->integer('prepaired_by');
            $table->integer('approved_by')->nullable();
            $table->string('your_reference_number',50)->nullable();
            $table->decimal('total_amount')->default(0);

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
        Schema::dropIfExists('bonus_claims');
    }
};