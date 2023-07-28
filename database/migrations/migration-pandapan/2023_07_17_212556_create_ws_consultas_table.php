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
        Schema::create('ws_consultas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('IdConsulta', 100)->unique('IdConsulta');
            $table->text('parametro');
            $table->string('tabla_destino', 150);
            $table->boolean('estado')->default(true);
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
        Schema::dropIfExists('ws_consultas');
    }
};
