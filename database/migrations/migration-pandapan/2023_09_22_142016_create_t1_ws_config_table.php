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
        Schema::create('t1_ws_config', function (Blueprint $table) {
            $table->integer('f1_id', true);
            $table->text('f1_url');
            $table->string('f1_NombreConexion', 50);
            $table->integer('f1_IdCia');
            $table->string('f1_IdProveedor', 50);
            $table->string('f1_Usuario', 50);
            $table->string('f1_separador', 1)->default('|');
            $table->string('f1_Clave', 128);
            $table->text('f1_AreaTrabajo');
            $table->boolean('f1_estado')->default(true);
            $table->string('f1_usuariointerno', 50)->nullable();
            $table->string('f1_claveinterno', 128)->nullable();
            $table->string('f1_ipinterno')->nullable();
            $table->string('f1_IdConsulta')->nullable();
            $table->string('f1_proxy_host')->nullable();
            $table->string('f1_proxy_port')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t1_ws_config');
    }
};
