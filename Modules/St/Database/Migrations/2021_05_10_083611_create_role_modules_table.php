<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('role_modules', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('module_id');

            //FOREIGN KEY CONSTRAINTS
            //$table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            //$table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id', 'module_id']);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_modules');
    }
}
