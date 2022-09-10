<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetonagiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setonagi_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('item_id')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->string('img')->nullable()->comment('商品画像');
            $table->string('price')->nullable()->comment('価格');
            $table->datetime('start_date')->nullable()->comment('掲載開始日');
            $table->datetime('end_date')->nullable()->comment('掲載終了日');

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
        Schema::dropIfExists('setonagi_items');
    }
}
