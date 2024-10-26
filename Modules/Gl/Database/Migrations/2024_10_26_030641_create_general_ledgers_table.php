<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledgers', function (Blueprint $table) {
           
            $table->id('general_ledger_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number')->index();
            $table->integer('branch_id')->index();
            $table->integer('gl_account_id')->index();
            $table->string('account_code');
            $table->integer('document_number');
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2); 
            $table->string('descriptions');
            $table->integer('module_id')->index();
            $table->integer('gl_account_analysis_id')->nullable();
            $table->integer('entity_id')->nullable();
            $table->integer('cheque_number')->nullable();
            $table->integer('cheque_reference_number')->nullable();
            $table->integer('reconciled')->default(0);
            $table->date('reconciled_date')->nullable();
            $table->integer('reconciled_user_id')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('general_ledgers');
    }
};

