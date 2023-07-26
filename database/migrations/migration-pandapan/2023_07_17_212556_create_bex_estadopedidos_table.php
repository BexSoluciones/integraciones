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
        Schema::create('bex_estadopedidos', function (Blueprint $table) {
            $table->string('codemp')->nullable();
            $table->string('codvend')->nullable()->index('in4');
            $table->string('tipoped')->nullable();
            $table->string('numped')->nullable();
            $table->string('nitcli')->nullable()->index('in1');
            $table->string('succli')->nullable()->index('in2');
            $table->string('fecped')->nullable();
            $table->string('ordenped')->nullable();
            $table->string('codpro')->nullable();
            $table->string('refer')->nullable();
            $table->string('descrip')->nullable();
            $table->string('cantped')->nullable();
            $table->string('vlrbruped')->nullable();
            $table->string('ivabruped')->nullable();
            $table->string('vlrnetoped')->nullable();
            $table->string('cantfacped')->nullable();
            $table->string('estado')->nullable();
            $table->string('tipo')->nullable();
            $table->string('tipofac')->nullable();
            $table->string('factura')->nullable();
            $table->string('ordenfac')->nullable();
            $table->string('cantfac')->nullable();
            $table->string('vlrbrufac')->nullable();
            $table->string('ivabrufac')->nullable();
            $table->string('vlrnetofac')->nullable();
            $table->string('obsped')->nullable();
            $table->string('undfacped')->nullable();
            $table->string('otromotivo')->nullable();
            $table->double('rowid')->nullable()->index('in5');
            $table->string('ws_id')->nullable();
            $table->bigInteger('codcliente')->nullable()->index('codcliente');
            $table->string('codvendedor', 4)->nullable()->index('codvendedor');
            $table->string('estadoenc', 4)->nullable()->index('estadoenc');

            $table->index(['nitcli', 'succli'], 'in3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_estadopedidos');
    }
};
