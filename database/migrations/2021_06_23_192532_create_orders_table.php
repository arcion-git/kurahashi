<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('cart_id')->unsigned()->nullable();
            $table->integer('tokuisaki_name')->unsigned()->nullable();
            $table->integer('store_name')->unsigned()->nullable();
            $table->string('price')->nullable();
            $table->integer('nouhin_yoteibi')->unsigned()->nullable();
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
        Schema::dropIfExists('orders');
    }
}
