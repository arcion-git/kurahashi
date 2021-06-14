<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_categories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('user_id')->nullable()->comment('ユーザーID');
            $table->string('category_id')->nullable()->comment('カテゴリID');

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
        Schema::dropIfExists('favorite_categories');
    }
}
