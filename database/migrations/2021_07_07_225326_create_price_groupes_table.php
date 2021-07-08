<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceGroupesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_groupes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('tokuisaki_id')->nullable()->comment('得意先コード');
            $table->string('store_id')->nullable()->comment('得意先店舗コード');
            $table->string('kigyou_id')->nullable()->comment('所管企業コード');
            $table->string('price_groupe')->nullable()->comment('価格グループコード');
            $table->string('price_groupe_name')->nullable()->comment('価格グループ名');
            $table->string('nebiki_ritsu')->nullable()->comment('値引率');

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
        Schema::dropIfExists('price_groupes');
    }
}
