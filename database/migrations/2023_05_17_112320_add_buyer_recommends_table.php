<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBuyerRecommendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::table('buyer_recommends', function (Blueprint $table) {
             $table->string('groupe')->nullable()->comment('グループ');
             $table->string('hidden_price')->nullable()->comment('価格非表示');
             $table->boolean('zaikokanri')->nullable()->comment('在庫管理');
             $table->string('zaikosuu')->nullable()->comment('在庫数');
             $table->string('uwagaki_item_name')->nullable()->comment('上書き商品名');
             $table->string('uwagaki_kikaku')->nullable()->comment('上書き規格');
             $table->string('gentei_store')->nullable()->comment('限定店舗');
         });

         // インデックスを作成
         Schema::table('buyer_recommends', function (Blueprint $table) {
             $table->index('item_id');
             $table->index('sku_code');
             $table->index('tokuisaki_id');
             $table->index('zaikokanri');
             $table->index('zaikosuu');
             $table->index('start');
             $table->index('end');
         });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::table('buyer_recommends', function (Blueprint $table) {
             $table->dropColumn('groupe');
             $table->dropColumn('hidden_price');
             $table->dropColumn('zaikokanri');
             $table->dropColumn('zaikosuu');
             $table->dropColumn('uwagaki_item_name');
             $table->dropColumn('uwagaki_kikaku');
             $table->dropColumn('gentei_store');
         });
     }
}
