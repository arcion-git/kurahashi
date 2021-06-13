<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('category_id')->nullable()->comment('カテゴリID');
            $table->string('busho_code')->nullable()->comment('部署コード');
            $table->string('busho_name')->nullable()->comment('部署名');
            $table->string('ka_code')->nullable()->comment('課コード');
            $table->string('bu_ka_name')->nullable()->comment('部課名');
            $table->string('category_name')->nullable()->comment('カテゴリ名');

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
        Schema::dropIfExists('categories');
    }
}
