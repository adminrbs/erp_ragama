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
        Schema::create('journal_entry_items', function (Blueprint $table) {
            $table->id('gl_journal_entry_item_id');
            $table->integer('gl_journal_id')->index();
            $table->integer('gl_account_id')->index();
            $table->integer('gl_account_analyse_id')->index();
            $table->decimal('amount', 15, 2); 
            $table->string('descriptions')->nullable();
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
        Schema::dropIfExists('journal_entry_items');
    }
};
