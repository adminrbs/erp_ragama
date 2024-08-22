<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ramsey\Uuid\Type\Decimal;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_request_others', function (Blueprint $table) {
            $table->id('purchase_request_other_id');
            $table->integer('purchase_request_Id')->nullable();
            $table->integer('internal_number')->default(0000);
            $table->string('external_number',200)->default(0000);
            $table->string('description',200)->nullable();
            $table->decimal('quantity',10,2)->nullable();
            $table->timestamps();

            $table->index('purchase_request_other_id','purc_requ_otherId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_request_others');
    }
};
