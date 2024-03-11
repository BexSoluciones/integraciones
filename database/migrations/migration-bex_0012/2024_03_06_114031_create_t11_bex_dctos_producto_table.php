<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT11BexDctosProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t11_bex_dctos_producto', function (Blueprint $table) {
            $table->string('codgrupodcto', 20)->nullable();
            $table->string('codproducto', 50)->nullable();
            $table->string('descuento', 10)->nullable();
            $table->string('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t11_bex_dctos_producto');
    }
}
