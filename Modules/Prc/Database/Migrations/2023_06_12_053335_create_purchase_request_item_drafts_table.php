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
        Schema::create('purchase_request_item_drafts', function (Blueprint $table) {
            $table->id('purchase_request_item_id');
            $table->integer('purchase_request_Id')->nullable();
            $table->integer('internal_number')->default(0);
            $table->integer('external_number')->default(0);
            $table->integer('item_id')->nullable();
            $table->string('item_name',200)->nullable();
            $table->decimal('quantity',10,2)->nullable();
            $table->string('unit_of_measure',50)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('package_size',10,2)->nullable();
            $table->timestamps();

            $table->index('purchase_request_item_id','purc_req_itemId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_request_item_drafts');
    }
};
