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
        Schema::create('return_transfer_items', function (Blueprint $table) {
            $table->id('return_transfer_item_id')->index();
            $table->integer('return_transfer_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('item_id')->index();
            $table->integer('customer_id')->index();
            $table->string('package_unit',100)->nullable();
            $table->integer('total_qty');
            $table->integer('transfer_qty');
            $table->integer('sales_return_reson_id')->index();
            $table->string('Remark',200)->nullable();
            $table->integer('sales_return_item_id')->index();
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
        Schema::dropIfExists('return_transfer_items');
    }
};
