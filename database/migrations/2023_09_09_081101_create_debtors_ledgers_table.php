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
        Schema::create('debtors_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('internal_number')->nullable();
            $table->string('external_number', 200)->nullable();
            $table->integer('document_number')->nullable();
            $table->date('trans_date')->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_code')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('paidamount', 15, 2)->nullable();
            $table->string('ref_no',200)->nullable();
            $table->integer('employee_id')->nullable(); 
            $table->decimal('return_amount', 15, 2)->nullable();
            $table->decimal('return_amount', 15, 2)->nullable();
            $table->string('manual_number',200)->nullable()->index();
            
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
        Schema::dropIfExists('debtors_ledgers');
    }
};
