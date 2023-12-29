<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT38BexEntregasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t38_bex_entregas', function (Blueprint $table) {
            $table->integer('id_entregas')->primary()->autoIncrement();
            $table->string('tipopedido', 30)->nullable();
            $table->string('numpedido', 10)->nullable()->index('numpedido');
            $table->string('numentrega', 10)->nullable()->index('numentrega');
            $table->string('placa', 10)->nullable();
            $table->string('conductor', 100)->nullable();
            $table->string('fecsalida', 10)->nullable();
            $table->string('fecentrega', 10)->nullable();
            $table->string('UnidadOperativa', 3)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t38_bex_entregas');
    }
}
