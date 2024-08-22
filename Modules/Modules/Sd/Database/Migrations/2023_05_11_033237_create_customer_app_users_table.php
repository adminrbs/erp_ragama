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
        Schema::create('customer_app_users', function (Blueprint $table) {
            $table->id('customer_app_user_id');
            $table->integer('customer_id');
            $table->Text('email',250)->nullable();
            $table->Text('mobile',50)->nullable();
            $table->Text('password',100);
            $table->integer('status_id')->default("1");
            $table->string('customer_code')->nullable();
            $table->string('address');
            $table->string('town_id');
            $table->string('session_key',250);
            $table->integer('branch_id');
            $table->timestamps();

            $table->index('customer_app_user_id','cus_app_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_app_users');
    }
};
