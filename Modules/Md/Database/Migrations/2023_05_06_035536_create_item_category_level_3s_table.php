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
        Schema::create('item_category_level_3s', function (Blueprint $table) {
            $table->id('Item_category_level_3_id')->index();
            $table->integer('Item_category_level_2_id')->index();
            $table->string('category_level_3',100);
            $table->boolean('is_active')->default("1");
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('Item_category_level_3_id','category_level_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_category_level_3s');
    }
};
