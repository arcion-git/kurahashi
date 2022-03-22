<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepeatordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeatorders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('cart_id')->unsigned()->nullable();
            $table->string('price')->unsigned()->nullable()->comment('金額');
            $table->integer('tokuisaki_name')->unsigned()->nullable()->comment('得意先名');
            $table->integer('store_name')->unsigned()->nullable()->comment('店舗名');
            $table->integer('quantity')->unsigned()->nullable()->comment('数量');
            $table->string('nouhin_youbi')->nullable()->comment('納品曜日');
            $table->string('startdate')->nullable()->comment('開始日');
            $table->string('status')->nullable()->comment('有効/無効');

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
        Schema::dropIfExists('repeatorders');
    }
}
