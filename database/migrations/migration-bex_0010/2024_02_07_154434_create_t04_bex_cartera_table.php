<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT04BexCarteraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t04_bex_cartera', function (Blueprint $table) {
            $table->string('nitcliente', 15)->nullable()->index('i_nit');
            $table->char('dv', 1)->nullable();
            $table->string('succliente', 20)->nullable()->index('i_suc');
            $table->string('codtipodoc', 5)->nullable();
            $table->string('documento', 20)->nullable();
            $table->date('fecmov')->nullable();
            $table->date('fechavenci')->nullable();
            $table->string('vrpostf', 15)->nullable();
            $table->string('valor', 15)->nullable();
            $table->string('codvendedor', 10)->nullable();
            $table->string('diasmora', 10)->nullable();
            $table->string('debcre', 1)->nullable();
            $table->string('codcliente', 17)->nullable();
            $table->string('recpro', 7)->nullable();
            $table->string('co_docto', 3)->nullable();
            $table->string('co_odc', 3)->nullable();
            $table->string('tipdoc_odc', 3)->nullable();
            $table->string('docto_odc', 8)->nullable();
            $table->string('planilla', 10)->nullable();
            $table->string('aux_cruce', 20)->nullable();
            $table->string('co_cruce', 3)->nullable();
            $table->string('un_cruce', 20)->nullable();
            $table->string('tipdoc_cruce', 3)->nullable();
            $table->string('numdoc_cruce', 8)->nullable();
            $table->string('estadotipodoc', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t04_bex_cartera');
    }
}
