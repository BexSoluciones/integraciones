<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT40BexDescuentosOfiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t40_bex_descuentos_ofi', function (Blueprint $table) {
            $table->string('LISTA')->nullable();
            $table->string('CODPRODUCTO')->nullable();
            $table->string('CANT1')->nullable();
            $table->string('DESC1')->nullable();
            $table->string('CANT2')->nullable();
            $table->string('DESC2')->nullable();
            $table->string('CANT3')->nullable();
            $table->string('DESC3')->nullable();
            $table->string('CANT4')->nullable();
            $table->string('DESC4')->nullable();
            $table->string('CANT5')->nullable();
            $table->string('DESC5')->nullable();
            $table->string('DESC_TOPE')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t40_bex_descuentos_ofi');
    }
}
