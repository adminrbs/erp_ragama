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
        Schema::create('item_altenative_names', function (Blueprint $table) {
            $table->id('item_altenative_name_id')->index();
            $table->string('item_altenative_name',200)->unique();
            $table->integer('status_id')->default("1");
            $table->timestamps();

            $table->index('item_altenative_name_id','altenative_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_altenative_names');
    }
};
