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
        Schema::create('t11_bex_dctos_producto', function (Blueprint $table) {
            $table->string('f11_codgrupodcto', 20)->nullable();
            $table->string('f11_codproducto', 50)->nullable();
            $table->string('f11_descuento', 10)->nullable();
            $table->string('f11_estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t11_bex_dctos_producto');
    }
};
