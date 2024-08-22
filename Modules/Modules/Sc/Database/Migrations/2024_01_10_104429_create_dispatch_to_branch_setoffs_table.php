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
        Schema::create('dispatch_to_branch_setoffs', function (Blueprint $table) {
            $table->id('dispatch_to_branch_setoffs_id')->index();
            $table->integer('internal_number')->default(0)->index();
            $table->string('external_number')->default(0)->index();
            $table->integer('dispatch_to_branch_item_id');
            $table->integer('item_history_setoff_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->integer('set_off_qty', 10, 2);
            $table->integer('received_qty', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('whole_sale_price',10,2)->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->string('batch_number',50)->nullable();
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
        Schema::dropIfExists('dispatch_to_branch_setoffs');
    }
};
