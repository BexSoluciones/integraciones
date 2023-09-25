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
        Schema::create('t8_bex_criterios_clientes', function (Blueprint $table) {
            $table->string('f8_cli_plancriterios', 3)->nullable();
            $table->string('f8_cli_criteriomayor', 10)->nullable();
            $table->string('f8_descripcion')->nullable();
            $table->string('f8_estado', 1)->nullable()->default('A');
            $table->string('f8_estado2', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t8_bex_criterios_clientes');
    }
};
