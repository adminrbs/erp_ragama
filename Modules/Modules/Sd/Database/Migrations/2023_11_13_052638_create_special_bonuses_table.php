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
        Schema::create('special_bonuses', function (Blueprint $table) {
            $table->id('special_bonus_id')->index();
            $table->integer('item_id')->index();
            $table->integer('quantity');
            $table->integer('bonus_quantity')->index();
            $table->integer('created_by')->index();
            $table->integer('updated_by')->nullable()->index();
            $table->integer('approved_by')->nullable()->index();
            $table->integer('valid_days')->nullable();
            $table->integer('status')->default(0);
            $table->integer('customer_id')->index();
            $table->string('remark',255)->nullable();
            $table->string('reject_remark',255)->nullable();
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
        Schema::dropIfExists('special_bonuses');
    }
};
