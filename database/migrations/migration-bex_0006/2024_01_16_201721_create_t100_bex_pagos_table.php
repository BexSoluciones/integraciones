<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT100BexPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t100_bex_pagos', function (Blueprint $table) {
            $table->string('codclientealt', 15)->nullable()->index('codclientealt');
            $table->string('succliente', 3)->nullable();
            $table->string('tipocredito', 30)->nullable();
            $table->string('numobligacion', 10)->nullable()->index('numobligacion');
            $table->string('fecpago', 20)->nullable();
            $table->string('valpago', 30)->nullable();
            $table->string('codcliente', 12)->nullable()->index('codcliente');
            $table->string('codobligacion', 10)->nullable()->index('codobligacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t100_bex_pagos');
    }
}
