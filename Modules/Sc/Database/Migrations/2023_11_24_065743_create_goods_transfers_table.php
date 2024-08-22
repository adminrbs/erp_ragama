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
        Schema::create('goods_transfers', function (Blueprint $table) {
            $table->id('goods_transfer_id')->index();
            $table->integer('internal_number')->index();
            $table->string('external_number',200)->index();
            $table->integer('document_number');
            $table->date('goods_transfer_date');
            $table->integer('from_branch_id')->index();
            $table->integer('from_location_id')->index();
            $table->integer('to_branch_id')->index();
            $table->integer('to_location_id')->index();
            $table->decimal('total_amount',15,2);
            $table->string('your_reference_number',50)->nullable();
            $table->string('remarks',200)->nullable();
            $table->integer('prepaired_by')->index()->nullable();
            $table->integer('approved_by')->index()->nullable();
            $table->integer('updated_by')->index()->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('goods_transfers');
    }
};
