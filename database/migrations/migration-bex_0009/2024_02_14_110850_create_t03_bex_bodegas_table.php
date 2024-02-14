<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT03BexBodegasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t03_bex_bodegas', function (Blueprint $table) {
            $table->string('codigo', 5)->nullable();
            $table->string('descripcion', 50)->nullable();
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
        Schema::dropIfExists('t03_bex_bodegas');
    }
}
