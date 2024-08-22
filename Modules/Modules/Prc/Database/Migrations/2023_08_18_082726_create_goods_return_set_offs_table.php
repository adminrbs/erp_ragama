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
        Schema::create('goods_return_set_offs', function (Blueprint $table) {
            $table->id('goods_received_return_setOff_id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number')->default(0);
            $table->integer('goods_received_return_item_id');
            $table->integer('item_history_setoff_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('set_off_qty', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('whole_sale_price',10,2)->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->string('batch_number',50)->nullable();
            $table->timestamps();

            $table->index('goods_received_return_setOff_id','gd_rec_ret_set_id');
        });


        DB::unprepared('
        CREATE TRIGGER item_history_set_offs_GR_return AFTER INSERT ON goods_return_set_offs
        FOR EACH ROW
        BEGIN
            -- Assign the values to variables
            SET @reference_internal_number := (SELECT internal_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @reference_external_number := (SELECT external_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @reference_document_number := (SELECT document_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @branch_id := (SELECT branch_id FROM goodreceivereturns WHERE external_number = NEW.external_number);
            SET @location_id := (SELECT location_id FROM goodreceivereturns WHERE external_number = NEW.external_number);
            SET @transaction_date :=(SELECT goods_received_date_time FROM goodreceivereturns WHERE external_number = NEW.external_number);
            SET @invoice_doc_number = (SELECT document_number FROM goodreceivereturns WHERE external_number = NEW.external_number);

            SET @qty := (
                SELECT 
                    SUM(quantity + COALESCE(free_quantity, 0)) 
                FROM 
                goodreceivereturn_items 
                WHERE 
                    external_number = NEW.external_number
            );
            
            INSERT INTO item_history_set_offs (internal_number, external_number, document_number,batch_number, branch_id, location_id, 	transaction_date, item_id, whole_sale_price, retial_price, cost_price,quantity,reference_internal_number,reference_external_number,reference_document_number,setoff_id)
            VALUES (NEW.internal_number, NEW.external_number, @invoice_doc_number, NEW.batch_number,@branch_id,@location_id,@transaction_date, NEW.item_id, NEW.whole_sale_price, NEW.retail_price, NEW.cost_price,-NEW.set_off_qty,@reference_internal_number, @reference_external_number,@reference_document_number,NEW.item_history_setoff_id);
  
        END;

        CREATE TRIGGER item_history_set_offs_setOffQTY_GR_RTN AFTER INSERT ON goods_return_set_offs
        FOR EACH ROW
        BEGIN
            UPDATE item_history_set_offs
            SET setoff_quantity = setoff_quantity + NEW.set_off_qty
            WHERE item_history_setoff_id = NEW.item_history_setoff_id;
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
        Schema::dropIfExists('goods_return_set_offs');
    }
};
