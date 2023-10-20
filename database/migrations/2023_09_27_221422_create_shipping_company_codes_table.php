<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingCompanyCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_company_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('company_code')->nullable()->comment('契約会社コード');
            $table->string('company_name')->nullable()->comment('契約会社名');
            $table->string('shipping_code')->nullable()->comment('配送コード');

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
        Schema::dropIfExists('shipping_company_codes', function (Blueprint $table) {
             $table->dropColumn('company_code');
             $table->dropColumn('company_name');
             $table->dropColumn('shipping_code');
        });
    }
}
