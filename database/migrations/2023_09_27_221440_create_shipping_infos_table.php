<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_infos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('shipping_code')->nullable()->comment('配送コード');
            $table->string('shipping_name')->nullable()->comment('配送名');
            $table->string('keiyaku_company_hyouji')->nullable()->comment('契約会社表示有無');
            $table->string('keiyaku_company_hissu')->nullable()->comment('契約会社必須');
            $table->string('price_groupe')->nullable()->comment('価格グループ');

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
        Schema::dropIfExists('shipping_infos', function (Blueprint $table) {
             $table->dropColumn('shipping_code');
             $table->dropColumn('shipping_name');
             $table->dropColumn('keiyaku_company_hyouji');
             $table->dropColumn('keiyaku_company_hissu');
             $table->dropColumn('price_groupe');
        });
    }
}
