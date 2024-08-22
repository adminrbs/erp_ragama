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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_id')->index()->unique();
            $table->Text('vehicle_no',10);
            $table->Text('vehicle_name',200)->nullable();
            $table->Text('description',250)->nullable();
            $table->integer('vehicle_type_id')->nullable();
            $table->date('licence_expire_date')->nullable();
            $table->date('insurance_expire_date')->nullable();
            $table->Text('remarks')->nullable();
            $table->integer('status_id')->default("1");
            $table->integer('branch_id')->nullable()->index();
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
        Schema::dropIfExists('vehicles');
    }
};
