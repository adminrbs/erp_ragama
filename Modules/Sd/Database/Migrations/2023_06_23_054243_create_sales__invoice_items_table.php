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
        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->id('sales_invoice_item_id');
            $table->integer('sales_invoice_Id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->default(0);
            $table->integer('item_id');
            $table->string('item_name',200)->nullable();
            $table->string('package_unit',50)->nullable();
            $table->decimal('quantity',10,2);
            $table->decimal('free_quantity',10,2)->nullable();
            $table->string('unit_of_measure',50)->nullable();
            $table->decimal('package_size',10,2)->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->decimal('discount_percentage',10,2)->default(0);
            $table->decimal('discount_amount',10,2)->default(0);
            $table->decimal('returned_qty',10,2)->default(0);
            $table->decimal('returned_foc',10,2)->default(0);
            $table->decimal('whole_sale_price', 10, 2)->nullable();
            $table->decimal('retial_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->timestamps();

            $table->index('sales_invoice_item_id','sales_inv_itemId');
            $table->index('sales_invoice_Id','sales_invoice_Id');
        });

        DB::unprepared('

        CREATE TRIGGER item_historys_delete_Sales_invoice AFTER DELETE ON sales_invoice_items
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
        Schema::dropIfExists('sales_invoice_items');
    }
};
