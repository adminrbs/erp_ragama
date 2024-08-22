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
        Schema::create('goods_received_note_items', function (Blueprint $table) {
            $table->id('goods_received_item_id')->index();
            $table->integer('goods_received_Id')->index();
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
            $table->integer('is_new_price')->nullable();
            $table->timestamps();

            $table->index('goods_received_item_id','goods_rec_itemId');
            $table->index('goods_received_Id','goods_rec_Id');
        });

        // $goodsReceivedIdColumn = 'goods_received_Id';
        DB::unprepared('
        CREATE TRIGGER purchase_order_note_items AFTER INSERT ON goods_received_note_items
         FOR EACH ROW
         BEGIN
             DECLARE new_qty INT;
            DECLARE new_free_qty INT;

            SET new_qty = NEW.quantity;
            SET new_free_qty = NEW.free_quantity;

            UPDATE purchase_order_note_items
        SET quantity_received = quantity_received + new_qty,
        free_received = free_received + new_free_qty
        WHERE purchase_order_item_id = NEW.purchase_order_item_id;
    
         END;


         CREATE TRIGGER purchase_order_note_items_delete AFTER DELETE ON goods_received_note_items
         FOR EACH ROW
         BEGIN
             DECLARE deleted_qty INT;
            DECLARE deleted_free_qty INT;

            SET deleted_qty = OLD.quantity;
            SET deleted_free_qty = OLD.free_quantity;

            UPDATE purchase_order_note_items
        SET quantity_received = quantity_received - deleted_qty,
        free_received = free_received - deleted_free_qty
        WHERE purchase_order_item_id = OLD.purchase_order_item_id;
    
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
        Schema::dropIfExists('goods_received_note_items');
    }
};
