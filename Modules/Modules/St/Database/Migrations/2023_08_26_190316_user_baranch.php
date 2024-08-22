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
        Schema::create('user_baranchs', function (Blueprint $table) {
            $table->integer('user_id')->index();
            $table->integer('branch_id')->index();
            $table->unique(['user_id','branch_id']);
           
            

          

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
        Schema::dropIfExists('user_baranchs');
    }
};
