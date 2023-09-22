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
        Schema::create('t29_bex_productos', function (Blueprint $table) {
            $table->string('f29_plu', 50)->nullable();
            $table->string('f29_descripcion', 50)->nullable();
            $table->string('f29_codigo', 50)->nullable();
            $table->string('f29_codunidademp', 4)->nullable();
            $table->string('f29_nomunidademp', 50)->nullable();
            $table->string('f29_factor', 20)->nullable();
            $table->string('f29_codproveedor', 20)->nullable();
            $table->string('f29_nomproveedor', 50)->nullable();
            $table->string('f29_codbarra', 20)->nullable();
            $table->string('f29_comb_009', 10)->nullable();
            $table->string('f29_comb_010', 10)->nullable();
            $table->string('f29_codmarca', 20)->nullable();
            $table->string('f29_nommarca', 50)->nullable();
            $table->string('f29_codunidadcaja', 4)->nullable();
            $table->string('f29_detalle', 50)->nullable();
            $table->string('f29_tipo_inv', 50)->nullable();
            $table->string('f29_ccostos', 10)->nullable();
            $table->string('f29_estado_unidademp', 1)->nullable()->default('A');
            $table->string('f29_estado', 1)->nullable()->default('A');
            $table->string('f29_estado_marca', 1)->nullable()->default('A');
            $table->string('f29_estadoproveedor', 1)->nullable()->default('A');
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
};
