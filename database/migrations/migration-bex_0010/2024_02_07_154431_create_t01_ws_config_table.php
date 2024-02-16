<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT01WsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t01_ws_config', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->text('url');
            $table->string('NombreConexion', 50);
            $table->integer('IdCia');
            $table->string('IdProveedor', 50);
            $table->string('Usuario', 50);
            $table->string('separador', 1)->default('|');
            $table->string('Clave', 128);
            $table->text('AreaTrabajo');
            $table->boolean('estado')->default(1);
            $table->string('usuariointerno', 50)->nullable();
            $table->string('claveinterno', 128)->nullable();
            $table->string('ipinterno')->nullable();
            $table->string('IdConsulta')->nullable();
            $table->enum('ConecctionType', ['api', 'db', 'planos', 'ws']);
            $table->string('proxy_host')->nullable();
            $table->string('proxy_port')->nullable();
            $table->string('urlEnvio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t01_ws_config');
    }
}
