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
        Schema::create('supplier_payment_methods', function (Blueprint $table) {
            $table->id('supplier_payment_method_id')->index();
            $table->string('supplier_payment_method',200);
            $table->boolean('is_active')->default(1);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('supplier_payment_method_id','supp_payment_method');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_payment_methods');
    }
};
