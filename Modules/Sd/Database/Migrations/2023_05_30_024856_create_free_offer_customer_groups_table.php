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
        Schema::create('free_offer_customer_groups', function (Blueprint $table) {
            $table->id('free_offer_customer_group_id');
            $table->integer('offer_id');
            $table->integer('customer_group_id');
            $table->timestamps();

            $table->index('free_offer_customer_group_id','fr_cus_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_offer_customer_groups');
    }
};
