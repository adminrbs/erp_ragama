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
        Schema::create('sales_invoice_copy_issueds', function (Blueprint $table) {
            $table->id('sales_invoice_copy_issued_id');
            $table->integer('sales_invoice_Id')->index();
            $table->integer('user_id')->index();
            $table->integer('empoyee_id')->index();
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
        Schema::dropIfExists('sales_invoice_copy_issueds');
    }
};
