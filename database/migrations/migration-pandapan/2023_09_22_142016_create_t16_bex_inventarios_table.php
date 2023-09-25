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
        Schema::create('t16_bex_inventarios', function (Blueprint $table) {
            $table->string('f16_bodega', 50)->nullable();
            $table->string('f16_iva', 20)->nullable();
            $table->string('f16_producto', 50)->nullable();
            $table->string('f16_inventario', 50)->nullable();
            $table->string('f16_estadoimpuesto', 1)->nullable()->default('A');
            $table->string('f16_estadobodega', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t16_bex_inventarios');
    }
};
