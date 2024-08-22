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
        Schema::create('supplier_contacts', function (Blueprint $table) {
            $table->id('supplier_contacts_id')->index();
            $table->integer('supplier_id')->nullable()->index();
            $table->string('contact_person',200)->nullable();
            $table->string('designation',200)->nullable();
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
        Schema::dropIfExists('supplier_contacts');
    }
};
