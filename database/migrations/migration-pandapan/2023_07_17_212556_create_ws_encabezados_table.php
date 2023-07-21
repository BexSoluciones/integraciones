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
        Schema::create('ws_tr_encabezados', function (Blueprint $table) {
            $table->string('fecha', 10)->nullable();
            $table->string('tipodoc', 4)->nullable();
            $table->string('consecutivo', 15)->nullable();
            $table->string('estado', 1)->nullable();
            $table->string('prefmov', 5)->nullable();
            $table->string('documento', 15)->nullable();
            $table->string('codfpagovta', 6)->nullable();
            $table->string('nitcliente', 15)->nullable()->index('i_s1e_tr_encabezados_nitcliente');
            $table->string('sucliente', 3)->nullable()->index('i_s1e_tr_encabezados_sucliente');
            $table->string('nombre', 50)->nullable();
            $table->string('placa', 6)->nullable();
            $table->string('cedulaconductor', 15)->nullable();
            $table->string('nomconductor', 50)->nullable();
            $table->string('codayudante', 15)->nullable();
            $table->string('nomayudante', 50)->nullable();
            $table->string('estadoregistro', 1)->nullable()->default('A')->index('i_s1e_tr_encabezados_estado');
            $table->string('codconductor', 4)->nullable()->index('i_s1e_tr_encabezados_codconductor');
            $table->string('codcliente', 17)->nullable()->index('i_s1e_tr_encabezados_codcliente');

            $table->index(['consecutivo', 'tipodoc', 'documento', 'prefmov'], 'i_s1e_tr_encabezados_compuesto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_tr_encabezados');
    }
};
