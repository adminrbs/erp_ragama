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
        Schema::create('delivery_plan_town_lists', function (Blueprint $table) {
            $table->id('delivery_plan_town_list_id');
            $table->integer('delivery_plan_id');
            $table->integer('district_id');
            $table->integer('town_id');
            $table->integer('order');
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
        Schema::dropIfExists('delivery_plan_town_lists');
    }
};
