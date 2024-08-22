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
        Schema::create('goodreceivereturns', function (Blueprint $table) {
            $table->id('goods_received_return_Id');
            $table->string('internal_number',200)->nullable();
            $table->string('external_number',200)->nullable();
            $table->date('goods_received_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_name',200)->nullable();
            /* $table->string('supplier_address',200); */
            $table->integer('purchase_order_id')->default(0);//need to change
            $table->string('supppier_invoice_number',200)->default(0);//need to change
            $table->decimal('invoice_amount',10,2)->nullable();
            $table->date('payment_due_date')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->decimal('discount_percentage',10,2)->nullable();
            $table->decimal('discount_amount',10,2)->nullable();
            $table->decimal('adjustment_amount',10,2)->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->integer('document_number')->default(1);
            $table->integer('prepaired_by');
            $table->integer('approved_by')->nullable();
            $table->string('your_reference_number',50)->nullable();
            $table->integer('goods_received_Id');
            $table->decimal('total_amount')->default(0);
            $table->timestamps();

            $table->index('goods_received_return_Id','gd_rece_ret_Id');
        });

        DB::unprepared('
        CREATE TRIGGER creditor_ledgers_insert AFTER INSERT ON goodreceivereturns
        FOR EACH ROW
        BEGIN
            -- Assign the values to variables
            SET @supplier_code := (SELECT supplier_code FROM suppliers WHERE supplier_id = NEW.supplier_id);
            SET @desc := CONCAT("Goods Returned to ",NEW.supplier_name);
        
            -- Execute the second trigger with the assigned values
            INSERT INTO creditors_ledger (internal_number, external_number, document_number, reference_internal_number, reference_external_number, reference_document_number, trans_date, description, branch_id, supplier_id, supplier_code,amount)
            VALUES (NEW.internal_number, NEW.external_number, NEW.document_number, NEW.internal_number, NEW.external_number, NEW.document_number, NEW.goods_received_date_time, @desc, NEW.branch_id, NEW.supplier_id, @supplier_code,NEW.total_amount);
        END;
        
        CREATE TRIGGER creditor_ledgers_delete AFTER DELETE ON goodreceivereturns
        FOR EACH ROW
        BEGIN
            DELETE FROM creditors_ledger WHERE internal_number = OLD.internal_number;
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
        Schema::dropIfExists('goodreceivereturns');
    }
};
