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
        Schema::create('purchase_request_drafts', function (Blueprint $table) {
            $table->id('purchase_request_Id');
            $table->integer('internal_number')->nullable();
            $table->integer('external_number')->nullable();
            $table->date('purchase_request_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            $table->date('expected_date')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->tinyText('remarks')->nullable();
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->integer('document_number')->nullable();
            $table->timestamps();

            $table->index('purchase_request_Id','purc_requestId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_request_drafts');
    }
};
