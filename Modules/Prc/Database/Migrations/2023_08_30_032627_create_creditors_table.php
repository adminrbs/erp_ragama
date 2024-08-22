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
        Schema::create('creditors_ledger', function (Blueprint $table) {
            $table->id('creditors_ledger_id');
            $table->integer('internal_number')->nullable();
            $table->string('external_number',200)->nullable();
            $table->integer('document_number')->nullable();
            $table->integer('reference_internal_number')->nullable();
            $table->string('reference_external_number',200)->nullable();
            $table->integer('reference_document_number')->nullable();
            $table->date('trans_date')->nullable();
            $table->string('description',255)->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_code')->nullable();
            $table->decimal('amount',10,2)->nullable();
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
        Schema::dropIfExists('creditors');
    }
};
