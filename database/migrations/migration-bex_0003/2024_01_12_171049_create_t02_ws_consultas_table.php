<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT02WsConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t02_ws_consultas', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('IdConsulta', 100)->unique('IdConsulta');
            $table->text('parametro');
            $table->string('tabla_destino', 150);
            $table->boolean('estado')->default(1);
            $table->text('descripcion');
            $table->integer('prioridad');
            $table->integer('desde')->default(0);
            $table->integer('cuantos')->default(250);
            $table->text('sentencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t02_ws_consultas');
    }
}
