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
        Schema::create('supply_groups', function (Blueprint $table) {
            $table->id('supply_group_id')->index();
            $table->text('supply_group', 100);
            $table->integer('status_id')->default("1");
           
            $table->timestamps();

            $table->index('supply_group_id','supply_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supply_groups');
    }
};
