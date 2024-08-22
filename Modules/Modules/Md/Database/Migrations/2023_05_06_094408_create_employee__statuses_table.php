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
        Schema::create('employee_statuses', function (Blueprint $table) {
            $table->id('employee_status_id')->index();
            $table->string('employee_status');
            $table->boolean('is_active')->default("1");
            $table->boolean('locked')->default(false);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('employee_status_id','emp_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_statuses');
    }
};
