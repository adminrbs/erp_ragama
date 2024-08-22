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
        Schema::create('purchase_order_note_items', function (Blueprint $table) {
            $table->id('purchase_order_item_id');
            $table->integer('purchase_order_Id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number')->default(0);
            $table->integer('item_id');
            $table->string('item_name',200)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('quantity',10,2);
            $table->decimal('quantity_received',10,2)->default(0);
            $table->decimal('free_quantity',10,2)->nullable();
            $table->decimal('free_received',10,2)->default(0);
            $table->string('unit_of_measure',50)->nullable();
            $table->decimal('package_size',10,2)->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->integer('is_new_price')->default(0);
            $table->timestamps();

            $table->index('purchase_order_item_id','purc_order_itemId');
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
        Schema::dropIfExists('purchase_order_note_items');
    }
};
