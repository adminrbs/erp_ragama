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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id('gl_journal_id');
            $table->string('reference_no')->nullable();
            $table->date('transaction_date');
            $table->string('remark')->nullable();
            $table->integer('branch_id')->index()->default(0);
            $table->integer('created_by')->index()->default(0);
            $table->integer('approved_by')->index();
            $table->integer('approval_status');
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
        Schema::dropIfExists('journal_entries');
    }
};
