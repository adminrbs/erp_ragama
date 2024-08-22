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
        Schema::create('goods_transfer_items', function (Blueprint $table) {
            $table->id('goods_transfer_items_id');
            $table->integer('goods_transfer_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->decimal('quantity',15,2);
            $table->integer('item_id')->index();
            $table->decimal('package_size',15,2)->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->decimal('whole_sale_price',15,2)->nullable();
            $table->decimal('retial_price',15,2)->nullable();
            $table->decimal('cost_price',15,2)->nullable();
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
        Schema::dropIfExists('goods_transfer_items');
    }
};
