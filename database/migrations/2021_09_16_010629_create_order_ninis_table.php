<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderNinisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_ninis', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('cart_nini_id')->unsigned()->nullable();
            $table->string('tokuisaki_name')->nullable();
            $table->string('store_name')->nullable();
            $table->string('price')->nullable();
            $table->string('nouhin_yoteibi')->nullable();
            $table->integer('quantity')->unsigned()->nullable();

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
        Schema::dropIfExists('order_ninis');
    }
}
