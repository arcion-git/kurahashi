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

            $table->string('item_id')->index()->nullable()->comment('商品コード');
            $table->string('sku_code')->nullable()->comment('SKUコード');
            $table->string('item_name')->nullable()->comment('商品名');
            // $table->string('item_name_kana')->nullable()->comment('商品名ひらがな');
            $table->string('keiyaku')->nullable()->comment('契約区分');
            $table->string('kikaku')->nullable()->comment('規格');
            $table->string('ninushi_code')->nullable()->comment('荷主コード');
            $table->string('ninushi_name')->nullable()->comment('荷主名');
            $table->string('sanchi_code')->nullable()->comment('産地コード');
            $table->string('sanchi_name')->nullable()->comment('産地名');
            $table->string('tani')->nullable()->comment('単位');
            $table->string('zaikosuu')->nullable()->comment('在庫数');
            $table->string('kigyou_code')->nullable()->comment('発注先企業コード');
            $table->string('busho_code')->nullable()->comment('発注先部署コード');
            $table->string('busho_name')->nullable()->comment('発注先部署名');
            $table->string('tantou_code')->nullable()->comment('発注先担当者コード');
            $table->string('tantou_name')->nullable()->comment('発注先担当者名');
            $table->string('jan_code')->nullable()->comment('JANコード');
            $table->string('nouhin_yoteibi_start')->nullable()->comment('納品予定日_開始');
            $table->string('nouhin_yoteibi_end')->nullable()->comment('納品予定日_終了');
            // $table->string('keisai_kigen')->nullable()->comment('掲載期限');
            $table->string('nyuukabi')->nullable()->comment('入荷日');
            $table->string('lot_bangou')->nullable()->comment('ロット番号');
            $table->string('lot_gyou')->nullable()->comment('ロット行');
            $table->string('lot_eda')->nullable()->comment('ロット枝');
            $table->string('souko_code')->nullable()->comment('倉庫コード');
            $table->text('tokkijikou')->nullable()->comment('特記事項');
            $table->string('haisou_simekiri_jikan')->nullable()->comment('配送締切時間');
            $table->string('haisou_nissuu')->nullable()->comment('配送日数');
            $table->string('shoudan_umu')->nullable()->comment('商談有無');
            $table->string('nebiki_umu')->nullable()->comment('値引有無');

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
