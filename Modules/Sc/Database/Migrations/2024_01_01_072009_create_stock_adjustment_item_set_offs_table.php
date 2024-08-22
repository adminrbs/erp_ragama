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
        Schema::create('stock_adjustment_item_set_offs', function (Blueprint $table) {
            $table->id('stock_adjusment_item_setoff_id');
            $table->integer('stock_adjusment_item_id');
            $table->integer('internal_number');
            $table->string('external_number');
            $table->integer('item_id');
            $table->integer('item_history_setoff_id');
            $table->integer('set_off_qty');
            $table->decimal('cost_price');
            $table->decimal('whole_sale_price');
            $table->decimal('retial_price');
            $table->decimal('batch_number');
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
        Schema::dropIfExists('stock_adjustment_item_set_offs');
    }
};
