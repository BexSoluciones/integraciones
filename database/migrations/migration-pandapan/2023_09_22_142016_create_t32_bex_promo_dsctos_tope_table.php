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
        Schema::create('t32_bex_promo_dsctos_tope', function (Blueprint $table) {
            $table->string('f32_idcia', 1)->nullable();
            $table->string('f32_rowid', 20)->nullable();
            $table->string('f32_estado', 1)->nullable();
            $table->string('f32_estado1', 1)->nullable();
            $table->date('f32_fini')->nullable();
            $table->date('f32_ffin')->nullable();
            $table->string('f32_co', 3)->nullable();
            $table->string('f32_codproducto', 15)->nullable();
            $table->decimal('f32_porcdcto', 5)->nullable();
            $table->string('f32_tipoinv', 10)->nullable();
            $table->string('f32_grupodctoitem', 4)->nullable();
            $table->string('f32_nitcliente', 15)->nullable();
            $table->string('f32_succliente', 4)->nullable();
            $table->string('f32_puntoenvio', 1)->nullable();
            $table->string('f32_tipocli', 4)->nullable();
            $table->string('f32_grupodctocli', 4)->nullable();
            $table->string('f32_condpago', 3)->nullable();
            $table->string('f32_listaprecios', 3)->nullable();
            $table->string('f32_planitem1', 3)->nullable();
            $table->string('f32_criteriomayoritem1', 4)->nullable();
            $table->string('f32_planitem2', 3)->nullable();
            $table->string('f32_criteriomayoritem2', 4)->nullable();
            $table->string('f32_plancli1', 3)->nullable();
            $table->string('f32_criteriomayorcli1', 10)->nullable();
            $table->string('f32_plancli2', 3)->nullable();
            $table->string('f32_criteriomayorcli2', 10)->nullable();
            $table->string('f32_codigoobsequi', 20)->nullable();
            $table->string('f32_motivoobsequio', 2)->nullable();
            $table->string('f32_umobsequio', 4)->nullable();
            $table->string('f32_cantobsequio', 10)->nullable();
            $table->string('f32_cantbaseobsequio', 10)->nullable();
            $table->string('f32_descripcion', 150)->nullable();
            $table->string('f32_factor', 50)->nullable();
            $table->string('f32_cupo', 50)->nullable();
            $table->string('f32_dctoval', 50)->nullable();
            $table->string('f32_x', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t32_bex_promo_dsctos_tope');
    }
};
