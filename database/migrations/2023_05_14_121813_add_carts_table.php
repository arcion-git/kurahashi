<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('addtype')->nullable()->comment('追加種別');
            $table->string('groupe')->nullable()->comment('グループ');
            $table->string('uwagaki_item_name')->nullable()->comment('上書き商品名');
            $table->string('uwagaki_kikaku')->nullable()->comment('上書き規格');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('addtype');
            $table->dropColumn('groupe');
            $table->dropColumn('uwagaki_item_name');
            $table->dropColumn('uwagaki_kikaku');
        });
    }
}
