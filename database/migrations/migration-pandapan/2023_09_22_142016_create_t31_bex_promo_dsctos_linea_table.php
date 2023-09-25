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
        Schema::create('t31_bex_promo_dsctos_linea', function (Blueprint $table) {
            $table->string('f31_idcia', 1)->nullable();
            $table->string('f31_rowid', 20)->nullable();
            $table->string('f31_descripcion', 40)->nullable();
            $table->string('f31_estado', 1)->nullable();
            $table->string('f31_estado1', 1)->nullable();
            $table->date('f31_fini')->nullable();
            $table->date('f31_ffin')->nullable();
            $table->string('f31_co', 3)->nullable();
            $table->string('f31_codproducto', 15)->nullable();
            $table->decimal('f31_porcdcto', 5)->nullable();
            $table->string('f31_tipoinv', 10)->nullable();
            $table->string('f31_grupodctoitem', 4)->nullable();
            $table->string('f31_nitcliente', 15)->nullable();
            $table->string('f31_succliente', 4)->nullable();
            $table->string('f31_puntoenvio', 1)->nullable();
            $table->string('f31_tipocli', 4)->nullable();
            $table->string('f31_grupodctocli', 4)->nullable();
            $table->string('f31_condpago', 3)->nullable();
            $table->string('f31_listaprecios', 3)->nullable();
            $table->string('f31_planitem1', 3)->nullable();
            $table->string('f31_criteriomayoritem1', 4)->nullable();
            $table->string('f31_planitem2', 3)->nullable();
            $table->string('f31_criteriomayoritem2', 4)->nullable();
            $table->string('f31_plancli1', 3)->nullable();
            $table->string('f31_criteriomayorcli1', 10)->nullable();
            $table->string('f31_plancli2', 3)->nullable();
            $table->string('f31_criteriomayorcli2', 10)->nullable();
            $table->string('f31_codigoobsequi', 20)->nullable();
            $table->string('f31_motivoobsequio', 2)->nullable();
            $table->string('f31_umobsequio', 4)->nullable();
            $table->string('f31_cantobsequio', 10)->nullable();
            $table->string('f31_cantbaseobsequio', 10)->nullable();
            $table->string('f31_indmaxmin', 1)->nullable();
            $table->string('f31_cantmin', 10)->nullable();
            $table->string('f31_cantmax', 10)->nullable();
            $table->string('f31_dctoval', 20)->nullable();
            $table->string('f31_escalacomb', 1)->nullable();
            $table->string('f31_contmaxmin', 1)->nullable();
            $table->string('f31_plancomb', 10)->nullable();
            $table->string('f31_prepack', 15)->nullable();
            $table->string('f31_valor_min', 16)->default('0');
            $table->string('f31_valor_max', 16)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t31_bex_promo_dsctos_linea');
    }
};
