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
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->id('sales_return_Id')->index()->unique();
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->default(0);
            $table->string('manual_number',200)->default(0);
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('sales_invoice_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('return_reason_id')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
            $table->date('order_date')->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->integer('delivery_plan_id')->nullable();
            $table->tinyText('remarks')->nullable();
            $table->integer('status')->default(0);    
           /*  $table->string('approval_status')->default('Pending'); */
            $table->integer('status')->default(0);
            $table->integer('book_id')->nullable();
            $table->integer('page_number')->nullable();
            $table->integer('document_number')->nullable();
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->string('your_reference_number')->nullable();
            $table->integer('sales_analyst_id')->nullable();
            $table->timestamps();
        });

        DB::unprepared('
      
        
        -- Trigger 01 
        CREATE TRIGGER debtor_ledger_setoffd_sales_return AFTER INSERT ON sales_returns
        FOR EACH ROW
        BEGIN
           
        
            -- Assign the value to the variable
            SET @customer_code := (SELECT customer_code FROM customers WHERE customer_id = NEW.customer_id);
            SET @reference_internal_number := (SELECT internal_number FROM sales_invoices WHERE sales_invoice_Id = NEW.sales_invoice_Id);
            SET @reference_external_number := (SELECT external_number FROM sales_invoices WHERE sales_invoice_Id = NEW.sales_invoice_Id);
            SET @document_number := (SELECT document_number FROM sales_invoices WHERE sales_invoice_Id = NEW.sales_invoice_Id);
            SET @cus_name := (SELECT customer_name FROM customers WHERE customer_id = NEW.customer_id);
            SET @desc := CONCAT("Sales Returned From ",@cus_name);
            -- Execute the trigger with the assigned values
            INSERT INTO debtors_ledger_setoffs (internal_number, external_number, document_number, reference_internal_number, reference_external_number, reference_document_number, trans_date, description, branch_id, customer_id, customer_code, amount)
            VALUES (NEW.internal_number, NEW.external_number, NEW.document_number, @reference_internal_number, @reference_external_number, @document_number, NEW.order_date, @desc, NEW.branch_id, NEW.customer_id, @customer_code, -NEW.total_amount);
        END;
        
        
        -- Trigger 02 
        CREATE TRIGGER debtor_ledger_setoffd_sales_return_DELETE AFTER DELETE ON sales_returns
        FOR EACH ROW
        BEGIN
            DELETE FROM debtors_ledger_setoffs WHERE internal_number = OLD.internal_number;
        END;
        

       
        
        
        -- Trigger 04 
        CREATE TRIGGER debtors_ledgers__sales_return_DELETE AFTER DELETE ON sales_returns
        FOR EACH ROW
        BEGIN
            DELETE FROM debtors_ledgers WHERE internal_number = OLD.internal_number;
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
        Schema::dropIfExists('sales_returns');
    }
};
