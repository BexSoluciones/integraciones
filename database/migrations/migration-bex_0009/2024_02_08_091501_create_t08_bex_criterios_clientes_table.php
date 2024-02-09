<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT08BexCriteriosClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t08_bex_criterios_clientes', function (Blueprint $table) {
            $table->string('cli_plancriterios', 3)->nullable();
            $table->string('cli_criteriomayor', 10)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('estado', 1)->default('A');
            $table->string('estado2', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t08_bex_criterios_clientes');
    }
}
