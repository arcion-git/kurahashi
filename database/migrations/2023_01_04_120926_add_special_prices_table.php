<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('special_prices', function (Blueprint $table) {
            $table->date('nouhin_end')->nullable()->comment('納品期限');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('special_prices', function (Blueprint $table) {
            $table->dropColumn('nouhin_end');
        });
    }
}
