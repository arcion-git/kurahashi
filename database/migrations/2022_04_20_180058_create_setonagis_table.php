<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetonagisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setonagis', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->nullable();

            $table->string('company')->nullable();
            $table->string('company_kana')->nullable();

            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();

            $table->string('last_name_kana')->nullable();
            $table->string('first_name_kana')->nullable();

            $table->string('address01')->nullable();
            $table->string('address02')->nullable();
            $table->string('address03')->nullable();
            $table->string('address04')->nullable();
            $table->string('address05')->nullable();

            $table->string('unei_company')->nullable();
            $table->string('pay')->nullable();

            $table->string('kakebarai_sinsa')->nullable();
            $table->string('kakebarai_riyou')->nullable();
            $table->boolean('setonagi_ok')->nullable();

            $table->integer('kakebarai_limit')->nullable();
            $table->integer('kakebarai_usepay')->nullable();
            $table->date('kakebarai_update_time')->nullable();

            $table->string('uketori_place')->nullable();
            $table->string('uketori_time')->nullable();
            $table->string('uketori_siharai')->nullable();

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
        Schema::dropIfExists('setonagis');
    }
}
