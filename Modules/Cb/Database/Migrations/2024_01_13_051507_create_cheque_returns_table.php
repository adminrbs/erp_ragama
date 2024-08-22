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
        Schema::create('cheque_returns', function (Blueprint $table) {
            $table->id('cheque_returns_id');
            $table->integer('internal_number')->index();
            $table->string('external_number',50)->index();
            $table->date('returned_date');
            $table->integer('branch_id')->index();
            $table->integer('customer_id')->index();
            $table->string('cheque_number',50);
            $table->decimal('bank_charges',15,2);
            $table->integer('document_number');
            $table->integer('is_redeposit_allowed')->default(0);
            $table->integer('cheque_dishonur_reason_id')->index();
            $table->integer('bank_charges_paid_by_customer')->default(0);
            $table->integer('returned_by');
            $table->decimal('amount',15,2);
            $table->integer('is_cancelled')->default(0);
            $table->integer('cheque_returns_id')->nullable();
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
        Schema::dropIfExists('cheque_returns');
    }
};
