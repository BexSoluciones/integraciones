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
        Schema::create('ws_config', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('url');
            $table->string('NombreConexion', 50);
            $table->integer('IdCia');
            $table->string('IdProveedor', 50);
            $table->string('Usuario', 50);
            $table->string('separador', 1)->default('|');
            $table->string('Clave', 128);
            $table->text('AreaTrabajo');
            $table->boolean('estado')->default(true);
            $table->string('usuariointerno', 50)->nullable();
            $table->string('claveinterno', 128)->nullable();
            $table->string('ipinterno')->nullable();
            $table->string('IdConsulta')->nullable();
            $table->string('proxy_host')->nullable();
            $table->string('proxy_port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_config');
    }
};
