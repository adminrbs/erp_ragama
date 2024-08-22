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
        Schema::create('customer_blocks', function (Blueprint $table) {
            $table->id('customer_block_id')->index();
            $table->integer('customer_id')->index();
            $table->integer('employee_id')->index();
            $table->boolean('is_blocked')->default(1);
            $table->string('remark',200)->nullable();
            $table->decimal('number_of_rders',10,2)->nullable();
            $table->decimal('value',10,2)->nullable();
            $table->date('release_date')->nullable();
            $table->string('customer_remark',200)->nullable();
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
        Schema::dropIfExists('customer_blocks');
    }
};
