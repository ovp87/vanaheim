<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyables', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->morphs('buyable');
            $table->timestamps();
        });

        /**
        Schema::table('buyables', function (Blueprint $table) {
            $table->unique(["buyable_id", "buyable_type"], 'buyable_unique');
            $table->unique("url");
        });
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buyables');
    }
}
