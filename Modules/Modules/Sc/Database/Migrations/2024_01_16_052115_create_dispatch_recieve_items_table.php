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
        Schema::create('dispatch_recieve_items', function (Blueprint $table) {
            $table->id('dispatch_recieve_item_id');
            $table->integer('dispatch_recieve_id');
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('quantity');
            $table->integer('item_id')->index();
            $table->decimal('package_unit',15,2)->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->decimal('whole_sale_price',15,2)->nullable();
            $table->decimal('retial_price',15,2)->nullable();
            $table->decimal('cost_price',15,2)->nullable();
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
        Schema::dropIfExists('dispatch_recieve_items');
    }
};
