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
        Schema::create('cheque_collections', function (Blueprint $table) {
            $table->id('cheque_collection_id');
            $table->string('internal_number',255);
            $table->string('external_number',255);
            $table->string('document_number',255);
            $table->integer('book_id');
            $table->integer('page_no');
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
        Schema::dropIfExists('cheque_collections');
    }
};
