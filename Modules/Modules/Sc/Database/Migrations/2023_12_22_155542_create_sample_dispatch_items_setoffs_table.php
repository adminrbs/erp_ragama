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
        Schema::create('sample_dispatch_items_setoffs', function (Blueprint $table) {
           
            $table->id('sample_dispatch_items_setoff_id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->nullable();
            $table->integer('sample_dispatch_item_id')->nullable();
            $table->integer('item_history_setoff_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('set_off_qty')->nullable();
            $table->decimal('cost_price',10,2)->nullable();
            $table->decimal('whole_sale_price',10,2)->nullable();
            $table->decimal('retail_price',10,2)->nullable();
            $table->string('batch_number',200)->nullable();

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
        Schema::dropIfExists('sample_dispatch_items_setoffs');
    }
};
