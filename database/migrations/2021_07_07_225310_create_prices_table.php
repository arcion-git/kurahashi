<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('price_groupe')->nullable()->comment('価格グループコード');
            $table->string('item_id')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->date('start')->nullable()->comment('掲載開始日');
            $table->date('end')->nullable()->comment('掲載期限');
            $table->integer('teika')->nullable()->comment('定価');
            $table->integer('price')->nullable()->comment('単価');

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
        Schema::dropIfExists('prices');
    }
}
