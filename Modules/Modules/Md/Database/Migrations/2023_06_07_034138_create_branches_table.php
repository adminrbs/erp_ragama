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
        Schema::create('branches', function (Blueprint $table) {
            $table->id('branch_id')->index()->unique();
            $table->string('branch_name',200);
            $table->Text('address')->nullable();
            $table->string('fixed_number',15)->nullable();
            $table->string('email',250)->nullable();
            $table->boolean('is_active')->nullable();
            $table->string('prefix',10)->nullable();
            $table->integer('code')->nullable();
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
        Schema::dropIfExists('branches');
    }
};
