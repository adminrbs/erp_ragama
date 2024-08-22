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
        Schema::create('cash_bundles', function (Blueprint $table) {
            $table->id('cash_bundles_id')->index();
            $table->string('internal_number',200)->nullable()->index();
            $table->string('external_number',200)->nullable()->index();
            $table->string('ho_remarks',255)->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->boolean('receipt_created')->nullable()->default(0);
            $table->integer('book_id')->nullable();
            $table->integer('page_no')->nullable();

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
        Schema::dropIfExists('cash_bundles');
    }
};
