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
        Schema::create('bex_promo_dsctos_linea', function (Blueprint $table) {
            $table->string('idcia', 1)->nullable();
            $table->string('rowid', 20)->nullable();
            $table->string('descripcion', 40)->nullable();
            $table->string('estado', 1)->nullable();
            $table->string('estado1', 1)->nullable();
            $table->date('fini')->nullable();
            $table->date('ffin')->nullable();
            $table->string('co', 3)->nullable();
            $table->string('codproducto', 15)->nullable();
            $table->decimal('porcdcto', 5)->nullable();
            $table->string('tipoinv', 10)->nullable();
            $table->string('grupodctoitem', 4)->nullable();
            $table->string('nitcliente', 15)->nullable();
            $table->string('succliente', 4)->nullable();
            $table->string('puntoenvio', 1)->nullable();
            $table->string('tipocli', 4)->nullable();
            $table->string('grupodctocli', 4)->nullable();
            $table->string('condpago', 3)->nullable();
            $table->string('listaprecios', 3)->nullable();
            $table->string('planitem1', 3)->nullable();
            $table->string('criteriomayoritem1', 4)->nullable();
            $table->string('planitem2', 3)->nullable();
            $table->string('criteriomayoritem2', 4)->nullable();
            $table->string('plancli1', 3)->nullable();
            $table->string('criteriomayorcli1', 10)->nullable();
            $table->string('plancli2', 3)->nullable();
            $table->string('criteriomayorcli2', 10)->nullable();
            $table->string('codigoobsequi', 20)->nullable();
            $table->string('motivoobsequio', 2)->nullable();
            $table->string('umobsequio', 4)->nullable();
            $table->string('cantobsequio', 10)->nullable();
            $table->string('cantbaseobsequio', 10)->nullable();
            $table->string('indmaxmin', 1)->nullable();
            $table->string('cantmin', 10)->nullable();
            $table->string('cantmax', 10)->nullable();
            $table->string('dctoval', 20)->nullable();
            $table->string('escalacomb', 1)->nullable();
            $table->string('contmaxmin', 1)->nullable();
            $table->string('plancomb', 10)->nullable();
            $table->string('prepack', 15)->nullable();
            $table->string('valor_min', 16)->default('0');
            $table->string('valor_max', 16)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_promo_dsctos_linea');
    }
};
