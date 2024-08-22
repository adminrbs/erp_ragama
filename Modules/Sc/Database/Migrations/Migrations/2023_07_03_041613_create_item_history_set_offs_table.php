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
        Schema::create('item_history_set_offs', function (Blueprint $table) {
            $table->id('item_history_setoff_id');
          /*   $table->integer('item_history_id')->nullable(); */
            $table->integer('internal_number')->nullable();
            $table->string('external_number',50)->nullable();
            $table->integer('document_number')->nullable();
            $table->string('batch_number',50)->nullable();
            $table->date('expire_date')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->date('transaction_date')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('whole_sale_price',10,2)->nullable();
            $table->decimal('retial_price',10,2)->nullable();
            $table->decimal('cost_price')->nullable();
            $table->decimal('quantity',10,2)->nullable();
            $table->decimal('setoff_quantity',10,2)->default(0);
            $table->integer('reference_internal_number')->nullable();
            $table->string('reference_external_number',50)->nullable();
            $table->integer('reference_document_number')->nullable();
            $table->integer('setoff_id')->nullable();
            $table->timestamps();

            $table->index('item_history_setoff_id','item_his_set_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_history_set_offs');
    }
};
