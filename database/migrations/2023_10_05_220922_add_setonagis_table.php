<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetonagisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setonagis', function (Blueprint $table) {
          //
          $table->string('shipping_code')->nullable()->comment('配送コード');
          $table->string('company_name')->nullable()->comment('契約会社名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setonagis', function (Blueprint $table) {
            //
            $table->string('shipping_code')->nullable()->comment('配送コード');
            $table->string('company_name')->nullable()->comment('契約会社名');
        });
    }
}
