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
        Schema::create('bex_tr_itemsfacts', function (Blueprint $table) {
            $table->string('tipodoc', 4)->nullable();
            $table->string('consecutivo', 15)->nullable();
            $table->string('prefmov', 5)->nullable();
            $table->string('documento', 15)->nullable();
            $table->string('cedulaconductor', 15)->nullable();
            $table->string('pluproducto', 7)->nullable();
            $table->string('nomproducto', 40)->nullable();
            $table->string('cajasmov', 6)->nullable();
            $table->string('unidadesmov', 6)->nullable();
            $table->string('unidadestot', 7)->nullable();
            $table->string('valortotal', 20)->nullable();
            $table->string('preciounitario', 10)->nullable();
            $table->string('factor', 5)->nullable();
            $table->string('tipoitem', 1)->nullable();
            $table->string('codbodega', 4)->nullable();
            $table->string('co_cru', 3)->nullable();
            $table->string('rowid', 20)->nullable();
            $table->string('estado', 1)->nullable()->default('A')->index('i_s1e_tr_itemsfacts_estado');
            $table->string('codconductor', 4)->nullable()->index('i_s1e_tr_encabezados_codconductor');

            $table->index(['consecutivo', 'tipodoc', 'documento', 'prefmov', 'pluproducto'], 'i_s1e_tr_itemsfacts_compuesto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_tr_itemsfacts');
    }
};
