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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id('stock_adjustment_id');
            $table->string('internal_number', 200);
            $table->string('external_number', 200);
            $table->integer('document_number');
            $table->date('date');
            $table->integer('branch_id');
            $table->integer('location_id');
            $table->string('your_reference_number')->nullable();
            $table->tinyText('remarks');
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
        Schema::dropIfExists('stock_adjustments');
    }
};
