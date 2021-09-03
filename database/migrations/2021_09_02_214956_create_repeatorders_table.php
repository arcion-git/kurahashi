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

            $table->integer('kaiin_number')->nullable()->comment('会員番号');;
            $table->string('item_id')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->string('store')->nullable()->comment('納品先店舗');
            $table->string('price')->nullable()->comment('金額');
            $table->string('status')->nullable()->comment('有効/無効');
            $table->date('nouhin_youbi')->nullable()->comment('納品曜日');

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
