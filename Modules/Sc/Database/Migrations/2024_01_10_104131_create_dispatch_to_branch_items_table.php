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
        Schema::create('dispatch_to_branch_items', function (Blueprint $table) {
            $table->id('dispatch_to_branch_item_id');
            $table->integer('dispatch_to_branch_id');
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('quantity');
            $table->integer('received_qty')->default(0);
            $table->integer('reversed_qty')->default(0);
            $table->integer('item_id')->index();
            $table->decimal('package_unit',15,2)->nullable();
            $table->decimal('price',15,2)->nullable();
            $table->decimal('whole_sale_price',15,2)->nullable();
            $table->decimal('retial_price',15,2)->nullable();
            $table->decimal('cost_price',15,2)->nullable();
            $table->string('batch_number',200)->nullable();
            $table->decimal('from_loc_rd_sale',15,2)->nullable();
            $table->integer('from_loc_qoh')->nullable();
            $table->decimal('to_loc_rd_sale',15,2)->nullable();
            $table->integer('to_loc_qoh')->nullable();
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
        Schema::dropIfExists('dispatch_to_branch_items');
    }
};
