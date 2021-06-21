<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');

            $table->string('tantou_id')->nullable()->comment('担当者コード');
            $table->string('name')->nullable()->comment('氏名');
            $table->string('name_kana')->nullable()->comment('フリガナ');
            $table->string('tel')->nullable()->comment('電話番号');
            $table->string('shozoku_busho_id')->nullable()->comment('所属部署コード');
            $table->string('shozoku_busho_name')->nullable()->comment('所属部署名');
            $table->string('kengen')->nullable()->comment('権限');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::drop('admins');
    }
}
