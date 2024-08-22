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
        Schema::create('sales_invoice_item_setoffs', function (Blueprint $table) {
            $table->id('sales_invoice_item_setoff_id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->nullable();
            $table->integer('sales_invoice_item_id')->nullable();
            $table->integer('item_history_setoff_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->decimal('set_off_qty')->nullable();
            $table->decimal('cost_price',10,2)->nullable();
            $table->decimal('whole_sale_price',10,2)->nullable();
            $table->decimal('retail_price',10,2)->nullable();
            $table->string('batch_number',200)->nullable();
            $table->timestamps();
        });

        
        DB::unprepared('
        CREATE TRIGGER item_history_set_offs AFTER INSERT ON sales_invoice_item_setoffs
        FOR EACH ROW
        BEGIN
            -- Assign the values to variables
            SET @reference_internal_number := (SELECT internal_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @reference_external_number := (SELECT external_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @reference_document_number := (SELECT document_number FROM item_history_set_offs WHERE item_history_setoff_id = NEW.item_history_setoff_id);
            SET @branch_id := (SELECT branch_id FROM sales_invoices WHERE external_number = NEW.external_number);
            SET @location_id := (SELECT location_id FROM sales_invoices WHERE external_number = NEW.external_number);
            SET @transaction_date :=(SELECT order_date_time FROM sales_invoices WHERE external_number = NEW.external_number);
            SET @invoice_doc_number = (SELECT document_number FROM sales_invoices WHERE external_number = NEW.external_number);
            SET @manuel_number := (SELECT manual_number FROM sales_invoices WHERE external_number = NEW.external_number);

            SET @qty := (
                SELECT 
                    SUM(quantity + COALESCE(free_quantity, 0)) 
                FROM 
                sales_invoice_items 
                WHERE 
                    external_number = NEW.external_number
            );
            
            INSERT INTO item_history_set_offs (internal_number, external_number, document_number,batch_number, branch_id, location_id, 	transaction_date, item_id, whole_sale_price, retial_price, cost_price,quantity,reference_internal_number,reference_external_number,reference_document_number,setoff_id,manual_number)
            VALUES (NEW.internal_number, NEW.external_number, @invoice_doc_number, NEW.batch_number,@branch_id,@location_id,@transaction_date, NEW.item_id, NEW.whole_sale_price, NEW.retail_price, NEW.cost_price,-NEW.set_off_qty,@reference_internal_number, @reference_external_number,@reference_document_number,NEW.item_history_setoff_id,@manuel_number);

            
        END;


        CREATE TRIGGER item_history_set_offs_setOffQTY AFTER INSERT ON sales_invoice_item_setoffs
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
        Schema::dropIfExists('sales_invoice_item_setoffs');
    }
};
