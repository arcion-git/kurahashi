<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartNinisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_ninis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('deal_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('tantou_name')->nullable();
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
        Schema::dropIfExists('cart_ninis');
    }
}
