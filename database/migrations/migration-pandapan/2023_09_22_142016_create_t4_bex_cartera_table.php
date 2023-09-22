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
        Schema::create('t4_bex_cartera', function (Blueprint $table) {
            $table->string('f4_cia', 10)->nullable();
            $table->string('f4_tercvendedor', 15)->nullable();
            $table->string('f4_nitcliente', 15)->nullable()->index('i_nit');
            $table->string('f4_succliente', 3)->nullable()->index('i_suc');
            $table->string('f4_codtipodoc', 3)->nullable();
            $table->string('f4_documento', 20)->nullable();
            $table->date('f4_fecmov')->nullable();
            $table->date('f4_fechavenci')->nullable();
            $table->string('f4_valor', 15)->nullable();
            $table->string('f4_debcre', 1)->nullable();
            $table->string('f4_recpro', 7)->nullable();
            $table->string('f4_co_docto', 3)->nullable();
            $table->string('f4_co_odc', 3)->nullable();
            $table->string('f4_tipdoc_odc', 3)->nullable();
            $table->string('f4_docto_odc', 8)->nullable();
            $table->string('f4_planilla', 10)->nullable();
            $table->string('f4_aux_cruce', 20)->nullable();
            $table->string('f4_co_cruce', 3)->nullable();
            $table->string('f4_un_cruce', 20)->nullable();
            $table->string('f4_tipdoc_cruce', 3)->nullable();
            $table->string('f4_numdoc_cruce', 8)->nullable();
            $table->string('f4_codcliente', 17)->nullable();
            $table->string('f4_estadotipodoc', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t4_bex_cartera');
    }
};
