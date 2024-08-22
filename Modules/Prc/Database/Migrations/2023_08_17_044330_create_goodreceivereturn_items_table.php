<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goodreceivereturn_items', function (Blueprint $table) {
            $table->id('goods_received_return_item_id');
            $table->integer('goods_received_return_Id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number')->default(0);
            $table->integer('item_id');
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
            /* $table->decimal('margin') */
            $table->string('batch_number', 200)->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('cost_price')->nullable();
            $table->timestamps();

            $table->index('goods_received_return_item_id','gd_rece_ret_itemId');
            $table->index('goods_received_return_Id','gd_rece_ret_Id');
        });

        DB::unprepared('
       

        CREATE TRIGGER item_historys_delete_goods_return AFTER DELETE ON goodreceivereturn_items
        FOR EACH ROW
        BEGIN
            DELETE FROM item_historys WHERE internal_number = OLD.internal_number;
        END;
     

    ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goodreceivereturn_items');
    }
};
