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
        Schema::create('supplier_groups', function (Blueprint $table) {
            $table->id('supplier_group_id')->index();
            $table->string('supplier_group_name',200);
            $table->boolean('is_active')->default(1);
            $table->boolean('system')->default("0");
            $table->timestamps();

            $table->index('supplier_group_id','supp_groupId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier_groups');
    }
};
