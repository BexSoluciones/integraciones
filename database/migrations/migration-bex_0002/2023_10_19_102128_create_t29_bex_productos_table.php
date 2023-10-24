<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateT29BexProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t29_bex_productos', function (Blueprint $table) {
            $table->string('plu', 50)->nullable();
            $table->string('descripcion', 50)->nullable();
            $table->string('codigo', 50)->nullable();
            $table->string('codunidademp', 4)->nullable();
            $table->string('nomunidademp', 50)->nullable();
            $table->string('factor', 20)->nullable();
            $table->string('codproveedor', 20)->nullable();
            $table->string('nomproveedor', 50)->nullable();
            $table->string('codbarra', 20)->nullable();
            $table->string('comb_009', 10)->nullable();
            $table->string('comb_010', 10)->nullable();
            $table->string('codmarca', 20)->nullable();
            $table->string('nommarca', 50)->nullable();
            $table->string('codunidadcaja', 4)->nullable();
            $table->string('detalle', 50)->nullable();
            $table->string('tipo_inv', 50)->nullable();
            $table->string('ccostos', 10)->nullable();
            $table->string('estado_unidademp', 1)->default('A');
            $table->string('estado', 1)->default('A');
            $table->string('estado_marca', 1)->default('A');
            $table->string('estadoproveedor', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t29_bex_productos');
    }
}
