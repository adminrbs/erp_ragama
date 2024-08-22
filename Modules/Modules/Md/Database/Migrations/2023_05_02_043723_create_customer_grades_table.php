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
        Schema::create('customer_grades', function (Blueprint $table) {
            $table->id('customer_grade_id')->index();
            $table->string('grade',100);
            $table->boolean('is_active')->default("1");
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('customer_grade_id','cus_gradeId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_grades');
    }
};
