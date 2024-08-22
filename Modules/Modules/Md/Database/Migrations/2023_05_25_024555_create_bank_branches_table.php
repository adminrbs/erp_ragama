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
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->id('bank_branch_id')->index();
            $table->integer('bank_id')->index();
            $table->Text('bank_branch_code');
            $table->Text('bank_branch_name');
            $table->integer('is_active')->default("1");
            $table->timestamps();

            $table->index('bank_branch_id','bank_branch');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_branches');
    }
};
