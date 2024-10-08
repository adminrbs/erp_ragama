<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');

            //FOREIGN KEY CONSTRAINTS
           // $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            //$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id', 'permission_id']);
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
        Schema::dropIfExists('roles_permissions');
    }
}
