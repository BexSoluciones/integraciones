<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t2_ws_consultas', function (Blueprint $table) {
            $table->integer('f2_id', true);
            $table->string('f2_IdConsulta', 100)->unique('f2_IdConsulta');
            $table->text('f2_parametro');
            $table->string('f2_tabla_destino', 150);
            $table->boolean('f2_estado')->default(true);
            $table->text('f2_descripcion');
            $table->integer('f2_prioridad');
            $table->integer('f2_desde')->default(0);
            $table->integer('f2_cuantos')->default(250);
            $table->text('f2_sentencia')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t2_ws_consultas');
    }
};
