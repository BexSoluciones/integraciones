<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT16BexInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t16_bex_inventarios', function (Blueprint $table) {
            $table->string('bodega', 50)->nullable();
            $table->string('iva', 20)->nullable();
            $table->string('producto', 50)->nullable();
            $table->string('inventario', 50)->nullable();
            $table->string('estadoimpuesto', 1)->default('A');
            $table->string('estadobodega', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t16_bex_inventarios');
    }
}
