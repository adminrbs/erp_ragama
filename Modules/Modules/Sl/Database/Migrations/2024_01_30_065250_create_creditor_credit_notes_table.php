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
        Schema::create('creditor_credit_note', function (Blueprint $table) {
            $table->id('creditor_credit_notes_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('branch_id')->index();
            $table->integer('supplier_id')->index();
            $table->decimal('amount');
            $table->date('trans_date');
            $table->string('narration_for_account',255);
            $table->string('description',200);
            $table->integer('created_by')->index();
            $table->integer('document_number');
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
        Schema::dropIfExists('creditor_credit_note.phps');
    }
};
