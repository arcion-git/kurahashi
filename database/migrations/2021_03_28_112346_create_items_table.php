<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('rank')->nullable()->comment('ランクグループ');
            $table->string('item_code')->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->string('item_name')->nullable()->comment('商品名');
            $table->string('keiyaku')->nullable()->comment('契約区分');
            $table->string('ninushi_code')->nullable()->comment('荷主コード');
            $table->string('ninushi_name')->nullable()->comment('荷主名');
            $table->string('sanchi_code')->nullable()->comment('産地コード');
            $table->string('sanchi_name')->nullable()->comment('産地名');
            $table->string('teika')->nullable()->comment('定価');
            $table->string('tanka')->nullable()->comment('単価');
            $table->string('tani')->nullable()->comment('単位');
            $table->string('zaikosuu')->nullable()->comment('在庫数');
            $table->string('kigyou_code')->nullable()->comment('発注先企業コード');
            $table->string('busho_code')->nullable()->comment('発注先部署コード');
            $table->string('busho_name')->nullable()->comment('発注先部署名');
            $table->string('tantou_code')->nullable()->comment('発注先担当者コード');
            $table->string('tantou_name')->nullable()->comment('発注先担当者名');
            $table->string('kokyaku_item_code')->nullable()->comment('顧客商品コード');
            $table->string('nouhin_yoteibi')->nullable()->comment('納品予定日');
            $table->string('keisai_kigen')->nullable()->comment('掲載期限');
            $table->string('nyuukabi')->nullable()->comment('入荷日');
            $table->string('lot_bangou')->nullable()->comment('ロット番号');
            $table->string('lot_gyou')->nullable()->comment('ロット行');
            $table->string('lot_eda')->nullable()->comment('ロット枝');
            $table->string('souko_code')->nullable()->comment('倉庫コード');
            $table->string('tokkijikou')->nullable()->comment('特記事項');
            $table->string('yokujitsuhaisou_simekirijikan')->nullable()->comment('翌日配送締切時間');

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
        Schema::dropIfExists('items');
    }
}
