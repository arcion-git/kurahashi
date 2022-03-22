<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepeatcartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repeatcarts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('kaiin_number')->nullable()->comment('会員番号');;
            $table->string('item_id')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');

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
        Schema::dropIfExists('repeatcarts');
    }
}
