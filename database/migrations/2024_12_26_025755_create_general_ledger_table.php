<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledger', function (Blueprint $table) {
            $table->id('general_ledger_id');
            $table->integer('internal_number');
            $table->string('external_number');
            $table->integer('document_number');
            $table->date('transaction_date')->nullable();
            $table->integer('gl_account_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->integer('gl_account_analyse_id')->default(1);
            $table->string('description', 255)->nullable();
            $table->integer('is_bank_rec')->default(0);
            $table->date('bank_rec_date')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('general_ledger');
    }
}

