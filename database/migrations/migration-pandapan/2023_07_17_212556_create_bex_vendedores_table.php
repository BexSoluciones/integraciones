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
        Schema::create('bex_vendedores', function (Blueprint $table) {
            $table->string('compania', 1)->nullable();
            $table->string('tercvendedor', 4)->nullable();
            $table->string('nomvendedor', 50)->nullable();
            $table->string('coddescuento', 20)->nullable();
            $table->string('codportafolio', 15)->nullable();
            $table->string('codsupervisor', 20)->nullable();
            $table->string('nomsupervisor', 50)->nullable();
            $table->string('nitvendedor', 50)->nullable();
            $table->string('centroop', 50)->nullable();
            $table->string('bodega', 50)->nullable();
            $table->string('tipodoc', 20)->nullable();
            $table->string('cargue', 30)->nullable();
            $table->string('estado', 1)->nullable()->default('A');
            $table->string('estadosuperv', 1)->nullable()->default('A');
            $table->string('codvendedor', 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_vendedores');
    }
};
