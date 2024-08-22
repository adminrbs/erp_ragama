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
        Schema::create('customer_transaction_alocations', function (Blueprint $table) {
            $table->id('customer_transaction_alocation_id')->index();
            $table->string('internal_number',200)->index();
            $table->string('external_number',200)->index();
            $table->integer('document_number');
            $table->integer('customer_id')->index();
            $table->integer('branch_id');
            $table->decimal('amount',15,2);
            $table->integer('created_by')->nullable()->index();
            
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
        Schema::dropIfExists('customer_transaction_alolcations');
    }
};
