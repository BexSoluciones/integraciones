<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT05BexClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t05_bex_clientes', function (Blueprint $table) {
            $table->increments('consecutivo');
            $table->string('codigo', 20)->nullable();
            $table->char('dv', 1)->nullable();
            $table->string('sucursal', 20)->nullable();
            $table->string('razsoc', 100)->nullable();
            $table->string('representante', 100)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('precio', 50)->nullable();
            $table->string('conpag', 50)->nullable();
            $table->string('periodicidad', 10)->nullable();
            $table->string('tercvendedor', 50)->nullable();
            $table->string('cupo', 20)->nullable();
            $table->string('nomconpag', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('barrio', 100)->nullable();
            $table->string('tipocliente', 30)->nullable();
            $table->string('cobraiva', 30)->nullable();
            $table->string('codpais', 30)->nullable();
            $table->string('coddpto', 30)->nullable();
            $table->string('codmpio', 30)->nullable();
            $table->string('codbarrio', 30)->nullable();
            $table->string('consec', 30)->nullable();
            $table->bigInteger('codcliente')->nullable();
            $table->string('estado', 1)->default('A');
            $table->string('estadofpagovta', 1)->default('A');
            
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
        Schema::dropIfExists('t05_bex_clientes');
    }
}
