<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('user_id')->unsigned()->nullable();
            $table->string('memo')->nullable();

            $table->string('status')->nullable();
            $table->string('uketori_siharai')->nullable();
            $table->string('uketori_place')->nullable();
            $table->string('uketori_time')->nullable();
            $table->datetime('start_time')->nullable();
            $table->datetime('kakunin_time')->nullable();
            $table->datetime('cancel_time')->nullable();
            $table->datetime('success_time')->nullable();
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
        Schema::dropIfExists('deals');
    }
}
