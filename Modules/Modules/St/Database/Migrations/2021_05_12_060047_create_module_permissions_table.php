<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('permission_id');

            //FOREIGN KEY CONSTRAINTS
            //$table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            //$table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['module_id', 'permission_id']);
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
        Schema::dropIfExists('module_permissions');
    }
}
