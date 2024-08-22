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
        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id('stock_adjusment_item_id');
            $table->integer('stock_adjusment_id');
            $table->integer('internal_number');
            $table->string('external_number');
            $table->integer('item_id');
            $table->string('item_name');
            $table->string('packsize');
            $table->integer('quantity');
            $table->decimal('cost_price');
            $table->decimal('whole_sale_price');
            $table->decimal('retial_price');
            $table->decimal('batch_number');
            $table->integer('cretae_by');
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
        Schema::dropIfExists('stock_adjustment_items');
    }
};
