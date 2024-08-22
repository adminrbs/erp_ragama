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
        Schema::create('direct_cheque_collections', function (Blueprint $table) {
            $table->id('direct_cheque_collection_id');
            $table->string('internal_number',200)->nullable()->index();
            $table->string('external_number',200)->nullable()->index();
            $table->string('ho_remarks',255)->nullable();
            $table->date('trans_date');
            $table->integer('status')->nullable()->default(0);
            $table->integer('document_number');
            $table->integer('ho_Received')->default(0);
            $table->integer('created_by');
            $table->integer('branch_id');
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
        Schema::dropIfExists('direct_cheque_collections');
    }
};
