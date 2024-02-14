<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT13BexEstadopedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t13_bex_estadopedidos', function (Blueprint $table) {
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
            $table->string('ws_id')->nullable();
            $table->string('codcliente', 20)->nullable()->index('codcliente');
            $table->string('rowid')->nullable()->index('in5');
            $table->string('codvendedor', 10)->nullable()->index('codvendedor');
            
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
        Schema::dropIfExists('t13_bex_estadopedidos');
    }
}
