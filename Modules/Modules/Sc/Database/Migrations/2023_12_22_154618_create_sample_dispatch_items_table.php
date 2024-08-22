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
        Schema::create('sample_dispatch_items', function (Blueprint $table) {
            $table->id('sample_dispatch_item_id')->index();
            $table->integer('sample_dispatch_id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->default(0);
            $table->integer('item_id');
            $table->string('item_name',200)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('quantity',10,2);
            $table->string('unit_of_measure',50)->nullable();
            $table->decimal('package_size',10,2)->nullable();
            $table->decimal('whole_sale_price', 10, 2)->nullable();
            $table->decimal('retial_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
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
        Schema::dropIfExists('sample_dispatch_items');
    }
};
