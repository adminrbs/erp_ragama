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
        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id('customer_contacts_id')->index();
            $table->integer('customer_id')->index();
            $table->string('contact_person',200)->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile',20)->nullable();
            $table->string('fixed',20)->nullable();
            $table->string('email',250)->nullable();
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
        Schema::dropIfExists('customer_contacts');
    }
};
