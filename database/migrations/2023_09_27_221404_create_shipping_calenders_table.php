<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingCalendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_calenders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('calender_id')->nullable()->comment('カレンダーID');
            $table->date('date')->nullable()->comment('日付');
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
        Schema::dropIfExists('shipping_calenders', function (Blueprint $table) {
             $table->dropColumn('calender_id');
             $table->dropColumn('date');
        });
    }
}
