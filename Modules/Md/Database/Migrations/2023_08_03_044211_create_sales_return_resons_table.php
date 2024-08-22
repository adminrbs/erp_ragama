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
        Schema::create('sales_return_resons', function (Blueprint $table) {
            $table->id('sales_return_reson_id')->index();
            $table->string('sales_return_resons',200);
            $table->boolean('is_active')->default(1);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('sales_return_reson_id','sales_ret_resonId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_return_resons');
    }
};
