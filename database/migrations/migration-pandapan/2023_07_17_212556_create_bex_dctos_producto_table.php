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
        Schema::create('bex_dctos_producto', function (Blueprint $table) {
            $table->string('codgrupodcto', 20)->nullable();
            $table->string('codproducto', 50)->nullable();
            $table->string('descuento', 10)->nullable();
            $table->string('estado', 1)->nullable()->default('A');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bex_dctos_producto');
    }
};
