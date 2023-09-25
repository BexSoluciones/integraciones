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
        Schema::create('t36_bex_vendedores', function (Blueprint $table) {
            $table->string('f36_compania', 1)->nullable();
            $table->string('f36_tercvendedor', 4)->nullable();
            $table->string('f36_nomvendedor', 50)->nullable();
            $table->string('f36_coddescuento', 20)->nullable();
            $table->string('f36_codportafolio', 15)->nullable();
            $table->string('f36_codsupervisor', 20)->nullable();
            $table->string('f36_nomsupervisor', 50)->nullable();
            $table->string('f36_nitvendedor', 50)->nullable();
            $table->string('f36_centroop', 50)->nullable();
            $table->string('f36_bodega', 50)->nullable();
            $table->string('f36_tipodoc', 20)->nullable();
            $table->string('f36_cargue', 30)->nullable();
            $table->string('f36_estado', 1)->nullable()->default('A');
            $table->string('f36_estadosuperv', 1)->nullable()->default('A');
            $table->string('f36_codvendedor', 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t36_bex_vendedores');
    }
};
