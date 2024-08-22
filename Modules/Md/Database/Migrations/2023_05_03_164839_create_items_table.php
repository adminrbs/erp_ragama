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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id')->index();
            $table->string('Item_code',50)->unique()->nullable();
            $table->string('item_Name',200);

            $table->string('item_description',200)->nullable();//1.4
            $table->integer('item_altenative_name_id')->nullable();

            $table->string('sku',200)->nullable();
            $table->string('barcode',50)->nullable();
            $table->string('unit_of_measure',50)->nullable();

            $table->decimal('whole_sale_price',10,2)->nullable();//1.4
            $table->decimal('retial_price',10,2)->nullable();
            $table->decimal('average_cost_price',10,2)->nullable();
            $table->decimal('previouse_purchase_price',10,2)->default(0);

            $table->decimal('package_size',10,2)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->string('storage_requirements',200)->nullable();

            $table->string('picture_url',200)->nullable();//1.4

            $table->integer('supply_group_id')->index();
            $table->integer('category_level_1_id')->index();
            $table->integer('category_level_2_id')->index();
            $table->integer('category_level_3_id')->index();

            $table->boolean('is_active');//1.4

            $table->decimal('minimum_order_quantity',10,2)->nullable();
            $table->decimal('maximum_order_quantity',10,2)->nullable();
            $table->decimal('reorder_level',10,2)->nullable();
            $table->decimal('reorder_quantity',10,2)->nullable();
            $table->boolean('manage_batch');
            $table->boolean('manage_expire_date');
            $table->boolean('allowed_free_quantity');
            $table->boolean('allowed_discount');
            $table->boolean('allowed_promotion');//1.4
            $table->tinyText('note')->nullable();
            $table->decimal('minimum_margin',15,2)->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('items');
    }
};