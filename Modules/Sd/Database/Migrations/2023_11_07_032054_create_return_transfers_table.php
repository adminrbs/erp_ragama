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
        Schema::create('return_transfers', function (Blueprint $table) {
            $table->id('return_transfer_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->date('transfer_date');
            $table->integer('branch_id');
            $table->integer('from_location_id')->index();
            $table->integer('to_location_id')->index();
            $table->integer('prepaired_by')->index();
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
        Schema::dropIfExists('return_transfers');
    }
};
