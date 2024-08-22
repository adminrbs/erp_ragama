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
        Schema::create('supplier_item_codes', function (Blueprint $table) {
            $table->id('supplier_item_code_id')->index();
            $table->integer('supplier_id')->index();
            $table->integer('item_id')->index();
            $table->string('supplier_item_code');
            $table->timestamps();

            $table->index('supplier_item_code_id','supp_item_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_item_codes');
    }
};
