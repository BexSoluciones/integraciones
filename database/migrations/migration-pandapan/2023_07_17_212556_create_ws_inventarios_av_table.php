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
        Schema::create('ws_inventarios_av', function (Blueprint $table) {
            $table->string('bodega', 50)->nullable();
            $table->string('iva', 20)->nullable();
            $table->string('producto', 50)->nullable();
            $table->string('inventario', 50)->nullable();
            $table->string('lote', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ws_inventarios_av');
    }
};
