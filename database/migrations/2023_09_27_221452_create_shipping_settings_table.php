<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('shipping_code')->nullable()->comment('配送コード');
            $table->string('shipping_method')->nullable()->comment('配送方法');
            $table->string('shipping_name')->nullable()->comment('配送名');
            $table->string('ukewatasibi_nyuuryoku_umu')->nullable()->comment('受渡し日入力有無');
            $table->string('ukewatasi_kiboujikan_umu')->nullable()->comment('受渡し希望時間有無');
            $table->string('calender_id')->nullable()->comment('カレンダーID');
            $table->string('shipping_price')->nullable()->comment('配送料');

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
        Schema::dropIfExists('shipping_settings', function (Blueprint $table) {
             $table->dropColumn('shipping_code');
             $table->dropColumn('shipping_method');
             $table->dropColumn('shipping_name');
             $table->dropColumn('keiyaku_company_hissu');
             $table->dropColumn('ukewatasibi_nyuuryoku_umu');
             $table->dropColumn('ukewatasi_kiboujikan_umu');
             $table->dropColumn('calender_id');
             $table->dropColumn('shipping_price');
        });
    }
}
