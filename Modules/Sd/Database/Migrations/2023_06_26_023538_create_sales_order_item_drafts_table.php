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
        Schema::create('sales_order_item_drafts', function (Blueprint $table) {
            $table->id('sales_order_item_id')->index();
            $table->integer('sales_order_Id')->index();
            $table->integer('internal_number');
            $table->string('external_number',50);
            $table->integer('item_id');
            $table->string('item_name')->nullable();
            $table->decimal('quantity',10,2);
            $table->decimal('free_quantity',10,2)->default(0);
            $table->string('unit_of_measure',50)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('package_size',10,2)->default(0);
            $table->decimal('price',10,2)->default(0);
            $table->decimal('discount_percentage',10,2)->default(0);
            $table->decimal('discount_amount',10,2)->default(0);
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
        Schema::dropIfExists('sales_order_item_drafts');
    }
};
