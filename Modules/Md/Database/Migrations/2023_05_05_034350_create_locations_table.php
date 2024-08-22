<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id('location_id')->index()->unique();
            $table->integer('branch_id')->index();
            $table->string('location_name',200);
            $table->tinyText('address')->nullable();
            $table->string('fixed_number',15)->nullable();
            $table->string('email',250)->nullable();
            $table->integer('location_type_id')->nullable();
            $table->boolean('Status')->nullable();
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
        Schema::dropIfExists('locations');
    }
};
