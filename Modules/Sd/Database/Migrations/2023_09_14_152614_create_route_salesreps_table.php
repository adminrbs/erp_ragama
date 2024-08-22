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
        Schema::create('route_employes', function (Blueprint $table) {
            $table->integer('route_id')->index();
            $table->integer('sales_rep_id')->index();
            $table->timestamps();
            $table->unique(['route_id','sales_rep_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_salesreps');
    }
};
