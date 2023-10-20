<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetonagiItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setonagi_items', function (Blueprint $table) {

          $table->string('price_groupe')->nullable()->comment('価格グループ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setonagi_items', function (Blueprint $table) {

          $table->string('price_groupe')->nullable()->comment('価格グループ');

        });
    }
}
