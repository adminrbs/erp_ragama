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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id('fund_transfer_id');
            //$table->string('reference_no')->nullable();
            $table->date('transaction_date');
            $table->decimal('amount', 15, 2);
            $table->integer('source_account_id')->index();
            $table->integer('destination_account_id')->index();
            $table->integer('source_branch_id')->index();
            $table->integer('destination_branch_id')->index();
            $table->string('description')->nullable();
            $table->integer('created_by')->index()->default(0);
            $table->integer('approved_by')->index()->default(0);
            $table->integer('approval_status')->default(0);
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
        Schema::dropIfExists('fund_transfers');
    }
};
