<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyerRecommendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyer_recommends', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tokuisaki_id')->nullable()->comment('得意先ID');
            $table->string('item_id')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->string('price')->nullable();
            $table->date('start')->nullable()->comment('掲載開始');
            $table->date('end')->nullable()->comment('掲載期限');
            $table->date('nouhin_end')->nullable()->comment('納品期限');
            $table->string('order_no')->nullable()->comment('並び順');

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
        Schema::dropIfExists('buyer_recommends');
    }
}
