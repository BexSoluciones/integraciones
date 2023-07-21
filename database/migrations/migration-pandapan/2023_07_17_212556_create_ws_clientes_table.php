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
        Schema::create('ws_clientes', function (Blueprint $table) {
            $table->integer('consecutivo', true);
            $table->string('codigo', 15)->nullable();
            $table->char('dv', 1)->nullable();
            $table->string('sucursal', 4)->nullable();
            $table->string('razsoc', 50)->nullable();
            $table->string('representante', 50)->nullable();
            $table->string('direccion', 50)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('precio', 50)->nullable();
            $table->string('conpag', 50)->nullable();
            $table->string('periodicidad', 10)->nullable();
            $table->string('tercvendedor', 50)->nullable();
            $table->string('cupo', 20)->nullable();
            $table->string('nomconpag', 50)->nullable();
            $table->string('barrio', 100)->nullable()->index('cli_barrios');
            $table->string('tipocliente', 30)->nullable();
            $table->string('cobraiva', 30)->nullable();
            $table->string('codpais', 100)->nullable();
            $table->string('coddpto', 30)->nullable();
            $table->string('codmpio', 30)->nullable();
            $table->string('codbarrio', 30)->nullable();
            $table->string('consec', 30)->nullable();
            $table->bigInteger('codcliente')->nullable()->index('codcliente');
            $table->string('estado', 1)->nullable()->default('A');
            $table->string('estadofpagovta', 1)->nullable()->default('A');

            $table->index(['codigo', 'sucursal', 'dv'], 'codig');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_clientes');
    }
};
