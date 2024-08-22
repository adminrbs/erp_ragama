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
        Schema::create('sample_dispatches', function (Blueprint $table) {
            $table->id('sample_dispatch_id');
            $table->integer('internal_number')->default(0);
            $table->string('external_number',200)->default(0);
            $table->string('manual_number',200)->default(0);
            $table->date('order_date_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('location_id')->nullable();
            /* $table->integer('employee_id')->nullable(); */
            $table->integer('customer_id')->nullable();
            $table->tinyText('remarks')->nullable();
            $table->string('approval_status')->default('Pending');
            $table->integer('document_number')->nullable();
            $table->integer('prepaired_by')->nullable();
            $table->integer('approved_by')->nullable();
            

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
        Schema::dropIfExists('sample_dispatches');
    }
};
