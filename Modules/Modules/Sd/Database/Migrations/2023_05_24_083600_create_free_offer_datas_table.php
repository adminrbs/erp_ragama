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
        Schema::create('free_offer_datas', function (Blueprint $table) {
            $table->id('offer_data_id')->index();
            $table->integer('offer_id')->index();
            $table->integer('item_id')->index();
            $table->integer('offer_type');
            $table->integer('offer_redeem_as');
            $table->boolean('is_active');
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
        Schema::dropIfExists('free_offer_datas');
    }
};
