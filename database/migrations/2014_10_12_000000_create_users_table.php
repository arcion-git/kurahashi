<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('kaiin_number')->unique()->nullable();
            $table->string('name')->nullable();
            $table->string('name_kana')->nullable();



            // $table->string('company')->nullable();
            // $table->string('company_kana')->nullable();

            // $table->string('address01')->nullable();
            // $table->string('address02')->nullable();
            // $table->string('address03')->nullable();
            // $table->string('address04')->nullable();
            // $table->string('address05')->nullable();

            $table->string('tel')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('setonagi')->nullable();
            $table->boolean('first_login')->nullable();
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
        Schema::drop('users');
    }
}
