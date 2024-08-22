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
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->id('sales_return_item_id');
            $table->integer('sales_return_Id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number', 200)->default(0);
            $table->integer('item_id');
            $table->string('item_name', 200)->nullable();
            $table->string('package_unit', 50)->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('free_quantity', 10, 2)->nullable();
            $table->string('unit_of_measure', 50)->nullable();
            $table->decimal('package_size', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('whole_sale_price', 10, 2)->nullable();
            $table->decimal('retial_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('discount_percentage', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->integer('sales_return_status')->default(0);
            $table->decimal('return_qty_transfer')->default(0);
            $table->timestamps();

            $table->index('sales_return_item_id','sal_ret_item_id');
            $table->index('sales_return_Id','sales_return_Id');
        });

        DB::unprepared('
        -- Trigger 01
        CREATE TRIGGER sales_invoice_items_on_return_qty AFTER INSERT ON sales_return_items
        FOR EACH ROW
        BEGIN
            SET @sales_invoice_id := (SELECT sales_invoice_id FROM sales_returns WHERE sales_return_Id = NEW.sales_return_Id);
            UPDATE sales_invoice_items
            SET returned_qty = NEW.quantity,
                returned_foc = COALESCE(NEW.free_quantity, 0)
            WHERE sales_invoice_items.sales_invoice_Id = @sales_invoice_id;
        END;

        -- Trigger 02
        CREATE TRIGGER item_historys_sales_return_items AFTER INSERT ON sales_return_items
        FOR EACH ROW
        BEGIN
       
        SET @location_id := (SELECT location_id FROM sales_returns WHERE external_number = NEW.external_number);
        SET @branch_id := (SELECT branch_id FROM sales_returns WHERE external_number = NEW.external_number);
        SET @date_time := (SELECT order_date FROM sales_returns WHERE external_number = NEW.external_number);
        SET @document_number := (SELECT document_number FROM sales_returns WHERE external_number = NEW.external_number);
        SET @total_qty := NEW.quantity + NEW.free_quantity;
        SET @ItemCode := (SELECT Item_code FROM items WHERE item_id = NEW.item_id);
        SET @cus_name := (SELECT customers.customer_name FROM sales_return_items INNER JOIN sales_returns ON sales_returns.sales_return_Id = sales_return_items.sales_return_Id INNER JOIN customers ON customers.customer_id = sales_returns.customer_id WHERE sales_return_items.sales_return_Id = NEW.sales_return_Id);
        SET @desc := CONCAT("Sales Returned From ",@cus_name);

        INSERT INTO item_historys (internal_number, external_number, branch_id, location_id, document_number, transaction_date,description, item_id, quantity, free_quantity,batch_number,whole_sale_price,retial_price,cost_price)
        VALUES (NEW.internal_number, NEW.external_number, @branch_id, @location_id, @document_number, @date_time,@desc, NEW.item_id, @total_qty, NEW.free_quantity,@ItemCode,NEW.whole_sale_price,NEW.retial_price,NEW.cost_price);
        END;

        -- Trigger 03
        CREATE TRIGGER item_historys_sales_return_items_delete AFTER DELETE ON sales_return_items
        FOR EACH ROW
        BEGIN
            DELETE FROM item_historys WHERE internal_number = OLD.internal_number;
        END;


        -- Trigger 04
        CREATE TRIGGER item_history_set_offs_sales_return AFTER INSERT ON sales_return_items
        FOR EACH ROW
        BEGIN
        
        SET @location_id := (SELECT location_id FROM sales_returns WHERE external_number = NEW.external_number);
        SET @branch_id := (SELECT branch_id FROM sales_returns WHERE external_number = NEW.external_number);
        SET @date_time := (SELECT order_date FROM sales_returns WHERE external_number = NEW.external_number);
        SET @document_number := (SELECT document_number FROM sales_returns WHERE external_number = NEW.external_number);
        SET @total_qty :=NEW.quantity + NEW.free_quantity;
        SET @sales_invoice_id := (SELECT sales_invoice_id FROM sales_returns WHERE external_number = NEW.external_number);

    IF @sales_invoice_id IS NULL OR @sales_invoice_id = "undefined" THEN
    INSERT INTO item_history_set_offs (internal_number, external_number, branch_id, location_id, document_number, transaction_date, item_id,whole_sale_price,retial_price,cost_price,quantity,reference_internal_number,reference_external_number,reference_document_number)
    VALUES (NEW.internal_number, NEW.external_number, @branch_id, @location_id, @document_number, @date_time, NEW.item_id,NEW.whole_sale_price,NEW.retial_price,NEW.cost_price,@total_qty,NEW.internal_number, NEW.external_number,@document_number);
       
    ELSE
        SET @ref_external_number := (SELECT external_number FROM sales_invoices WHERE sales_invoice_Id =  @sales_invoice_id);
        SET @ref_internal_number := (SELECT internal_number FROM sales_invoices WHERE sales_invoice_Id =  @sales_invoice_id);
        SET @ref_doc_number := (SELECT document_number FROM sales_invoices WHERE sales_invoice_Id =  @sales_invoice_id);

    INSERT INTO item_history_set_offs (internal_number, external_number, branch_id, location_id, document_number, transaction_date, item_id,whole_sale_price,retial_price,cost_price,quantity,reference_internal_number,reference_external_number,reference_document_number)
        VALUES (NEW.internal_number, NEW.external_number, @branch_id, @location_id, @document_number, @date_time, NEW.item_id,NEW.whole_sale_price,NEW.retial_price,NEW.cost_price,@total_qty,@ref_internal_number,@ref_external_number,@ref_doc_number);
    END IF;
         
        
        END;

        
        -- Trigger 05
        CREATE TRIGGER item_history_set_offs_sales_return_item_delete AFTER DELETE ON sales_return_items
        FOR EACH ROW
        BEGIN
            
            DELETE FROM item_history_set_offs WHERE internal_number = OLD.internal_number;
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
        Schema::dropIfExists('sales_return_items');
    }
};
