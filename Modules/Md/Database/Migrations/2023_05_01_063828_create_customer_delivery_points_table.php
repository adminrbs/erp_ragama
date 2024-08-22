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
        Schema::create('customer_delivery_points', function (Blueprint $table) {
            $table->id('customer_delivery_id')->index();
            $table->integer('customer_id')->index();
            $table ->string('destination',200)->nullable();
            $table ->tinyText('address')->nullable();
            $table ->string('mobile',20)->nullable();
            $table ->string('fixed')->nullable();
            $table ->tinyText('instruction')->nullable();
            $table ->tinyText('google_map_link')->nullable();
            $table->timestamps();

            $table->index('customer_delivery_id','cus_deliveId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_delivery_points');
    }
};
