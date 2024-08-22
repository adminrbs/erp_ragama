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
        Schema::create('delivery_types', function (Blueprint $table) {
            $table->id('delivery_type_id')->index();
            $table->string('delivery_type_name',200);
            $table->boolean('is_active')->default(1);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('delivery_type_id','delivery_typeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_types');
    }
};
