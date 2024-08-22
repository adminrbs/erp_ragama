<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_offer_ranges', function (Blueprint $table) {
            $table->id('free_offer_range_id');
            $table->integer('offer_data_id');
            $table->decimal('from',10,2)->nullable();
            $table->decimal('to',10,2)->nullable();
            $table->decimal('free_offer_quantity',10,2)->nullable();
            $table->decimal('maximum_quantity',10,2)->nullable();
            $table->decimal('free_offer_value',10,2)->nullable();
            $table->decimal('maximum_value',10,2)->nullable();
            $table->decimal('total_offer_quantity',10,2)->default(0);
            $table->decimal('total_offer_value',10,2)->default(0);
            $table->integer('free_offer_another_item_id')->nullable();
            $table->timestamps();

            $table->index('free_offer_range_id','fr_offer_rangeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_offer_ranges');
    }
};
