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
        Schema::create('reprint_requests', function (Blueprint $table) {
            $table->id('reprint_requests_id');
            $table->integer('sales_invoice_Id')->index();
            $table->integer('customer_id')->index();
            $table->integer('request_branch_id')->index();
            $table->integer('requested_by')->index();
            $table->integer('request_status')->default(0);
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
        Schema::dropIfExists('reprint_requests');
    }
};
