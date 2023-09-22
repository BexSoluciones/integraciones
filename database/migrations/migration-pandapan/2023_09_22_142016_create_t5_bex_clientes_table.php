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
        Schema::create('t5_bex_clientes', function (Blueprint $table) {
            $table->integer('f5_consecutivo', true);
            $table->string('f5_codigo', 15)->nullable();
            $table->char('f5_dv', 1)->nullable();
            $table->string('f5_sucursal', 4)->nullable();
            $table->string('f5_razsoc', 50)->nullable();
            $table->string('f5_representante', 50)->nullable();
            $table->string('f5_direccion', 50)->nullable();
            $table->string('f5_telefono', 50)->nullable();
            $table->string('f5_precio', 50)->nullable();
            $table->string('f5_conpag', 50)->nullable();
            $table->string('f5_periodicidad', 10)->nullable();
            $table->string('f5_tercvendedor', 50)->nullable();
            $table->string('f5_cupo', 20)->nullable();
            $table->string('f5_nomconpag', 50)->nullable();
            $table->string('f5_barrio', 100)->nullable()->index('cli_barrios');
            $table->string('f5_tipocliente', 30)->nullable();
            $table->string('f5_cobraiva', 30)->nullable();
            $table->string('f5_codpais', 100)->nullable();
            $table->string('f5_coddpto', 30)->nullable();
            $table->string('f5_codmpio', 30)->nullable();
            $table->string('f5_codbarrio', 30)->nullable();
            $table->string('f5_email', 50)->nullable();
            $table->string('f5_consec', 30)->nullable();
            $table->bigInteger('f5_codcliente')->nullable()->index('codcliente');
            $table->string('f5_estado', 1)->nullable()->default('A');
            $table->string('f5_estadofpagovta', 1)->nullable()->default('A');

            $table->index(['f5_codigo', 'f5_sucursal', 'f5_dv'], 'codig');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t5_bex_clientes');
    }
};
