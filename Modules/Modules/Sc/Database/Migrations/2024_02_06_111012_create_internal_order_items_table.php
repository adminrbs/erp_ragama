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
        Schema::create('internal_order_items', function (Blueprint $table) {
            $table->id('internal_order_items_id');
            $table->integer('internal_orders_id');
            $table->integer('internal_number');
            $table->string('external_number',50);
            $table->integer('item_id');
            $table->string('item_name')->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('quantity',10,2);
            $table->decimal('from_branch_stock',10,2);
            $table->decimal('to_branch_stock',10,2);
            $table->decimal('avg_sales',10,2);
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
        Schema::dropIfExists('internal_order_items');
    }
};
