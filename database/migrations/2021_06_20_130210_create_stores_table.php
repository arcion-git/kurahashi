<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('kigyou_id')->nullable()->comment('所管企業コード');
            $table->string('tokuisaki_id')->nullable()->comment('得意先コード');
            $table->string('store_id')->nullable()->comment('得意先店舗コード');
            $table->string('tokuisaki_name')->nullable()->comment('得意先名');
            $table->string('store_name')->nullable()->comment('得意先店舗名');
            $table->string('torihiki_shubetu')->nullable()->comment('取引種別');
            $table->string('tantou_id')->nullable()->comment('営業担当者コード');
            $table->string('yuubin')->nullable()->comment('郵便番号');
            $table->string('jyuusho1')->nullable()->comment('住所１');
            $table->string('jyuusho2')->nullable()->comment('住所２');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('fax')->nullable()->comment('ＦＡＸ番号');
            $table->string('haisou_yuubin')->nullable()->comment('配送先_郵便番号');
            $table->string('haisou_jyuusho1')->nullable()->comment('配送先_住所１');
            $table->string('haisou_jyuusho2')->nullable()->comment('配送先_住所２');
            $table->string('haisou_tel')->nullable()->comment('配送先_電話番号');
            $table->string('haisou_fax')->nullable()->comment('配送先_ＦＡＸ番号');
            $table->string('haisou_route')->nullable()->comment('配送ルート');

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
        Schema::dropIfExists('stores');
    }
}
