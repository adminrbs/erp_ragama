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
        Schema::create('bonus_claim_items', function (Blueprint $table) {
            $table->id('bonus_claim_item_id')->index();
            $table->integer('bonus_claim_Id')->index();
            $table->integer('internal_number')->default(0);
            $table->string('external_number')->default(0);
            $table->integer('item_id')->index();
            $table->string('item_name', 200)->nullable();
            $table->string('package_unit', 50)->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('free_quantity', 10, 2)->nullable();
            $table->string('unit_of_measure', 50)->nullable();
            $table->decimal('package_size', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount_percentage', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            /* $table->decimal('discount_amount') */
            $table->decimal('whole_sale_price', 10, 2)->nullable();
            $table->decimal('retial_price', 10, 2)->nullable();
            $table->decimal('previouse_whole_sale_price',10,2)->default(0);
            $table->decimal('previouse_retail_price',10,2)->default(0);
            /* $table->decimal('margin') */
            $table->string('batch_number', 200)->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->integer('purchase_order_item_id')->nullable();
            $table->decimal('additional_bonus',10,2)->nullable();
            $table->decimal('additional_bonus_received',10,2)->nullable();
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
        Schema::dropIfExists('bonus_claim_items');
    }
};
