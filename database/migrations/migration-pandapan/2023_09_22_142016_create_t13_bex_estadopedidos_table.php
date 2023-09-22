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
        Schema::create('t13_bex_estadopedidos', function (Blueprint $table) {
            $table->string('f13_codemp')->nullable();
            $table->string('f13_codvend')->nullable()->index('in4');
            $table->string('f13_tipoped')->nullable();
            $table->string('f13_numped')->nullable();
            $table->string('f13_nitcli')->nullable()->index('in1');
            $table->string('f13_succli')->nullable()->index('in2');
            $table->string('f13_fecped')->nullable();
            $table->string('f13_ordenped')->nullable();
            $table->string('f13_codpro')->nullable();
            $table->string('f13_refer')->nullable();
            $table->string('f13_descrip')->nullable();
            $table->string('f13_cantped')->nullable();
            $table->string('f13_vlrbruped')->nullable();
            $table->string('f13_ivabruped')->nullable();
            $table->string('f13_vlrnetoped')->nullable();
            $table->string('f13_cantfacped')->nullable();
            $table->string('f13_estado')->nullable();
            $table->string('f13_tipo')->nullable();
            $table->string('f13_tipofac')->nullable();
            $table->string('f13_factura')->nullable();
            $table->string('f13_ordenfac')->nullable();
            $table->string('f13_cantfac')->nullable();
            $table->string('f13_vlrbrufac')->nullable();
            $table->string('f13_ivabrufac')->nullable();
            $table->string('f13_vlrnetofac')->nullable();
            $table->string('f13_obsped')->nullable();
            $table->string('f13_undfacped')->nullable();
            $table->string('f13_otromotivo')->nullable();
            $table->double('f13_rowid')->nullable()->index('in5');
            $table->string('f13_bex_id')->nullable();
            $table->bigInteger('f13_codcliente')->nullable()->index('codcliente');
            $table->string('f13_codvendedor', 4)->nullable()->index('codvendedor');
            $table->string('f13_estadoenc', 4)->nullable()->index('estadoenc');

            $table->index(['f13_nitcli', 'f13_succli'], 'in3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t13_bex_estadopedidos');
    }
};
