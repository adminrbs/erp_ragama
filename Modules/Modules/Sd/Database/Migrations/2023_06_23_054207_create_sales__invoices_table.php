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
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id('sales_invoice_Id')->index();
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->default(0);
            $table->string('manual_number',200)->default(0);
            $table->date('order_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->integer('payment_term_id')->nullable();
            $table->integer('payment_method_id')->nullable();
            $table->string('your_reference_number',50)->nullable();
            $table->tinyText('delivery_instruction')->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->integer('document_number')->nullable();
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('sales_order_Id')->nullable();
            $table->integer('delivery_plan_id')->nullable();
            $table->boolean('is_delivery_planned')->default(false);
            $table->boolean('is_picking_list')->default(false);
            $table->integer('picking_list_id')->nullable();
            $table->integer('no_of_prints')->nullable();
            $table->boolean('is_postpone_delivery')->default(0);
            $table->string('postpone_reason',100)->nullable();
            $table->integer('postpone_by')->nullable();
            $table->date('postpone_date_time')->nullable();
            $table->integer('postponed')->default(0);
            $table->boolean('is_reprint_allowed')->default(0);
            $table->boolean('is_inquery_created')->default(0);
            $table->integer('sales_analyst_id')->nullable();


            $table->timestamps();
        });

        DB::unprepared('
        CREATE TRIGGER debtors_ledgers_set_offs_insert AFTER INSERT ON sales_invoices
        FOR EACH ROW
        BEGIN
            -- Assign the values to variables
            SET @customer_code := (SELECT customer_code FROM customers WHERE customer_id = NEW.customer_id);
            SET @cus_name := (SELECT customer_name FROM customers WHERE customer_id = NEW.customer_id);
            SET @desc := CONCAT("Sales Invoice to ",@cus_name);
            -- Execute the second trigger with the assigned values
            INSERT INTO debtors_ledger_setoffs (internal_number, external_number, document_number, reference_internal_number, reference_external_number, reference_document_number, trans_date, description, branch_id, customer_id, customer_code,amount)
            VALUES (NEW.internal_number, NEW.external_number, NEW.document_number, NEW.internal_number, NEW.external_number, NEW.document_number, NEW.order_date_time, @desc, NEW.branch_id, NEW.customer_id, @customer_code,NEW.total_amount);
        END;
        

        CREATE TRIGGER debtors_ledgers_setoffs_delete AFTER DELETE ON sales_invoices
        FOR EACH ROW
        BEGIN
            DELETE FROM debtors_ledger_setoffs WHERE internal_number = OLD.internal_number;
        END;



        CREATE TRIGGER debtors_ledgers_insert AFTER INSERT ON sales_invoices
        FOR EACH ROW
        BEGIN
            -- Assign the values to variables
            SET @customer_code := (SELECT customer_code FROM customers WHERE customer_id = NEW.customer_id);
            SET @cus_name := (SELECT customer_name FROM customers WHERE customer_id = NEW.customer_id);
            SET @descr := CONCAT("Sales Invoice to ",@cus_name);
            -- Execute the second trigger with the assigned values
            INSERT INTO debtors_ledgers (internal_number, external_number, document_number, trans_date, description, branch_id, customer_id, customer_code,amount,paidamount,employee_id,manual_number,sales_analyst_id)
            VALUES (NEW.internal_number, NEW.external_number, NEW.document_number, NEW.order_date_time, @descr, NEW.branch_id, NEW.customer_id, @customer_code,NEW.total_amount,0,NEW.employee_id,NEW.manual_number,NEW.sales_analyst_id);
        END;
        

        
        CREATE TRIGGER debtors_ledgers_delete AFTER DELETE ON sales_invoices
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
        Schema::dropIfExists('sales_invoices');
    }
};
