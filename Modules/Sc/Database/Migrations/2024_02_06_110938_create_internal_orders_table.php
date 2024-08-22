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
        Schema::create('internal_orders', function (Blueprint $table) {
            $table->id('internal_orders_id')->index();
            $table->integer('internal_number')->nullable();
            $table->string('external_number',50)->nullable()->index();
            $table->integer('document_number')->nullable();
            $table->date('order_date_time')->nullable();
            $table->integer('from_branch_id')->nullable()->index();
            $table->integer('to_branch_id')->nullable()->index();
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('status')->default(0);
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
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
        Schema::dropIfExists('internal_orders');
    }
};
