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
        Schema::create('reverse_devision_transfer_items', function (Blueprint $table) {
            $table->id('reverse_devision_transfer_items_id');
            $table->integer('reverse_devision_transfer_id');
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('quantity');
            $table->integer('item_id')->index();
            $table->decimal('package_unit',15,2)->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->decimal('whole_sale_price',15,2)->nullable();
            $table->decimal('retial_price',15,2)->nullable();
            $table->decimal('cost_price',15,2)->nullable();
            $table->integer('dispatch_to_branch_item_id')->index();
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
        Schema::dropIfExists('reverse_devision_transfer_items');
    }
};
