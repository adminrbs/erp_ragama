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
        Schema::create('supplygroup_employees', function (Blueprint $table) {
            $table->integer('supply_group_id')->index();
            $table->integer('sales_rep_id')->index();
            $table->timestamps();
            $table->unique(['supply_group_id','sales_rep_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplygroup_salesrep');
    }
};
