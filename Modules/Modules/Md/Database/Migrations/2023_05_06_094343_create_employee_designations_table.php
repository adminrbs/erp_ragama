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
        Schema::create('employee_designations', function (Blueprint $table) {
            $table->id('employee_designation_id')->index();
            $table->string('employee_designation');
            $table->boolean('is_active')->default("1");
            $table->boolean('locked')->default(false);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('employee_designation_id','emp_designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_designations');
    }
};
